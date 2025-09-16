<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GradingResults extends Component
{
    public $students;              // list for dropdown
    public $selectedStudentId;     // chosen mahasiswa user_id
    public $selectedStudent;       // full user + relations
    public $format = '100';    // grading format (can switch later)
    public $dosenGrades;

    
    public function mount($studentId = null)
    {
        // Only fetch mahasiswa users for dropdown
        $this->students = User::whereHas('mahasiswaProfile')->get();

        if ($studentId) {
            $this->updatedSelectedStudentId($studentId);
        }
    }

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

    // untuk pergantian format penilaian
    public function updatedFormat()
    {
        // Recalculate grades every time the format changes
        $this->updateGrades();
    }

    // menggupdate penilaian tiap pergantian mahasiswa
    protected function updateGrades()
    {
        $project = $this->selectedStudent->projects;
        $this->dosenGrades = $this->calculateDosenGrades($project, $this->format);
    }

    protected function calculateDosenGrades($project, $format)
    {
        return $project->grades
            ->groupBy('dosen_id')
            ->map(function ($grades) use ($format) {
                $finalGrade = $grades->reduce(function ($carry, $grade) use ($format) {
                    return $carry + ($grade->grade * ($grade->gradeType->percentage / 100) * $format);
                }, 0);

                return (object) [
                    'dosen' => $grades->first()->dosen,
                    'final_grade' => $finalGrade,
                ];
            })
            ->values();
    }

    protected function calculateGrade() {
        // Fetch the student and their projects
        $project = $this->selectedStudent->projects;

        // Extract dosen grades separately
        $this->dosenGrades = $this->calculateDosenGrades($project, $this->format);

        

        // Keep student as-is without polluting it
        $this->selectedStudent->setRelation('projects', $project);
    }

    public function updatedSelectedStudentId($studentId)
    {
        
        // Eager load the full graph when user is selected
        $user = User::with([
            'mahasiswaProfile',
            'projects.grades.dosen'
        ])
        ->has('mahasiswaProfile')
        ->find($studentId);

        $this->selectedStudent = $user ?? null;

        if ($this->selectedStudent) {
            $this->calculateGrade();
        }
        
        // dd($this->selectedStudent->projects);
    }

    public function render()
    {
        return view('livewire.grading-results');
    }
}
