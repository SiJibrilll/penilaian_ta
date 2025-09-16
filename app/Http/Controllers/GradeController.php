<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\User;
use App\Models\GradeType;
use App\Http\Requests\StoreGradeRequest;
use App\Models\Project;
use Illuminate\Support\Arr;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id = session('studentId') ?? null;

        return view('hasil', [
            "id" => $id
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mahasiswa = User::students()->get();

        $dosen = User::dosen()->with('dosenProfile')->get();

        $gradeTypes = GradeType::all();

        return view('penilaian', [
            "mahasiswa" => $mahasiswa,
            "dosen" => $dosen,
            "penilaian" => $gradeTypes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGradeRequest $request)
    {
        $validated = $request->validated();
        
        

        // 1. Get student’s project
        $project = Project::where('user_id', $validated['student'])->firstOrFail();
    

        // 2. Get dosen from form input (not from auth)
        $dosen = User::findOrFail($validated['dosen']);
        $role = $dosen->dosenProfile->role;
        
        // 3. Check if already graded by this role
        $alreadyGraded = Grade::where('project_id', $project->id)
            ->whereHas('dosen.dosenProfile', function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->exists();

        if ($alreadyGraded) {
            return redirect()
                ->back()
                ->withErrors([
                    'dosen' => "Mahasiswa ini sudah dinilai oleh seorang {$role}."
                ])
                ->withInput();
        }

        $format = match ($validated['format']) {
            'format1' => 4,
            'format2' => 10,
            'format3' => 100,
            default   => 100,
        };

        $gradesData = [];

        foreach (Arr::except($validated, ['dosen', 'student', 'format']) as $gradeTypeId => $value) {
            if (is_numeric($gradeTypeId)) {
                $gradesData[] = [
                    'grade_type_id' => $gradeTypeId,   // from input name
                    'grade' => $value / $format,                 // from input value
                    'dosen_id' => $dosen->id,
                    'project_id' => $project->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        Grade::insert($gradesData);

        return redirect('/grades')->with('studentId', $project->user_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('hasil', [
            "id" => $id
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
