<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;

class GradingResults extends Component
{
    public $roles;                  // role dosen
    public $students;              // list for dropdown
    public $selectedStudentId;     // chosen mahasiswa user_id
    public $selectedStudent;       // full user + relations
    public $format = '100';    // grading format 
    public $dosenGrades;
    public $cummulativeGrade;

    
    public function mount($studentId = null)
    {
        // Only fetch mahasiswa users for dropdown
        $this->students = User::whereHas('mahasiswaProfile')->get();
        $this->roles = $this->getEnumValues('dosen_profiles', 'role');

        if ($studentId) {
            $this->updatedSelectedStudentId($studentId);
        }
    }

    // untuk mengambil enum dalam table
    function getEnumValues($table, $column) {
        $column = DB::selectOne("
            SELECT COLUMN_TYPE 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = ? 
            AND COLUMN_NAME = ?
        ", [$table, $column]);

        preg_match("/^enum\((.*)\)$/", $column->COLUMN_TYPE, $matches);

        return array_map(fn($val) => trim($val, "'"), explode(',', $matches[1]));
    }

    // ubah mahasiswa yang terpilih
    public function updatedSelectedStudentId($studentId)
    {
        // Eager load the full graph when user is selected
        $user = User::with([
            'mahasiswaProfile',
            'projects.grades.dosen'
        ])
        ->has('mahasiswaProfile')
        ->find($studentId);

        if ($user) {
            $this->selectedStudent = $user;
            $this->initiate();
        }
        
        
    }

    function initiate() {
        $user = $this->selectedStudent;
        

        //calculate grades
        $calculatedGrade = $this->calculateDosenGrades($user->projects, $this->format);

        
        //arrange grades
        $this->dosenGrades = $this->arrangeGrade($this->roles, $calculatedGrade);

        // cek apabila sudah bisa menampilkan nilai kumulatif
        if(!$this->checkCummulative()) {
            $this->cummulativeGrade = null;
            return;
        }

        //bila semua dapat, maka hitung nilai kumulatif
        $this->countCummulativeGrade();
    }

    // hitung total nilai yang diberikan seorang dosen
protected function calculateDosenGrades($project, $format)
{
    return $project->grades
        ->groupBy('dosen_id')
        ->map(function ($grades) use ($format) {
            // 1. Hitung nilai gabungan normalisasi (0 - 1)
            $finalGrade = $grades->reduce(function ($carry, $grade) {
                return $carry + ($grade->grade * ($grade->gradeType->percentage / 100));
            }, 0);

            // 2. Konversi ke format pilihan (misal 0-100, 0-10, 0-4)
            $finalGrade *= $format;

            return (object) [
                'dosen' => $grades->first()->dosen,
                'final_grade' => $finalGrade,
            ];
        })
        ->values();
}

    // susun nilai yang sudah dihitung menjadi tabel
    function arrangeGrade($rows, $data) {
        
        return collect($rows)->mapWithKeys(function ($row) use ($data) {
            $match = collect($data)->first(function ($item) use ($row) {
                return strtoupper($item->dosen->dosenProfile->role) === strtoupper($row);
            });

            return [
                $row => $match 
                    ? ['dosen' => $match->dosen, 'grade' => $match->final_grade, 'role' => $row]
                    : ['dosen' => '-', 'grade' => '-', 'role' => $row],
            ];
        });
    }

    // untuk pergantian format penilaian
    public function updatedFormat()
    {
        // Recalculate grades every time the format changes
        $this->initiate();
    }

    // periksa apakah semua dosen sudah memberi nilai
    function checkCummulative() {
        return collect($this->dosenGrades)->every(function ($item) {
            return $item['grade'] !== '-';
        });
    }

    //berikan nilai kumulatif
    function countCummulativeGrade() {
       $totalGrade = collect($this->dosenGrades)
        ->avg(function ($item) {
            return $this->normalizeGrade($item['grade'], $this->format);
        });

        $this->cummulativeGrade = \DB::table('grade_parameters')
        ->where('min', '<=', $totalGrade)
        ->orderByDesc('min')
        ->value('name') ?? "E";

    }

    // normalisasi nilai untuk perhitungan nilai kumulatif
    function normalizeGrade($grade, $format) {
        return match ($format) {
            '4'   => ($grade / 4) * 100,
            '10'  => ($grade / 10) * 100,
            '100' => $grade, // already percentage
            default => $grade, // fallback
        };
    }

    function deleteGrade($dosenId) {
        if ($this->selectedStudent->projects->finlized) {
            return;
        }

        Grade::where('dosen_id', $dosenId)
        ->where('project_id', $this->selectedStudent->projects->id)
        ->delete();

        $this->initiate();
    }

    function finalize() {
        // cek apakah nilai kumulatif sudah lengkap
        if (!$this->checkCummulative()) {
            return;
        }

        $project = $this->selectedStudent->projects;
        $project->finalized = true;
        $project->save();
        $this->initiate();
    }

    public function render()
    {
        return view('livewire.grading-results');
    }
}
