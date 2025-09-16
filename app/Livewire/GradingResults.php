<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class GradingResults extends Component
{
    public $students;              // list for dropdown
    public $selectedStudentId;     // chosen mahasiswa user_id
    public $selectedStudent;       // full user + relations
    public $format = 'average';    // grading format (can switch later)

    
    public function mount($studentId = null)
    {
        // Only fetch mahasiswa users for dropdown
        $this->students = User::whereHas('mahasiswaProfile')->get();

        if ($studentId) {
            $this->updatedSelectedStudentId($studentId);
        }
    }

    protected function calculateGrade() {

        $project = $this->selectedStudent->projects;
        $dosenGrades = $project->grades
        ->groupBy('dosen_id') // group grades by dosen
        ->map(function($grades, $dosenId) {
            return $grades->reduce(function($carry, $grade) {
                // Weighted grade = grade * percentage / 100
                return $carry + ($grade->grade * ($grade->gradeType->percentage / 100));
            }, 0);
        });

        // $dosenGrades is now an array with dosen_id => final grade
        $project->dosenGrades = $dosenGrades;
        $this->selectedStudent->projects = $project;
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
