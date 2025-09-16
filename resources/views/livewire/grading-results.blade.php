<div class="container">
    <div class="header">
        <h1>TA Results Display</h1>
        <p>Tampilan Hasil Penilaian Tugas Akhir</p>
    </div>
    
    {{-- Student Dropdown --}}
    <div class="form-group">
        <label for="studentSelect">Pilih Mahasiswa</label>
        <select wire:model.live="selectedStudentId">
            <option value="">-- Pilih Mahasiswa --</option>
            @foreach ($students as $student)
                <option value="{{ $student->id }}">
                    {{ $student->name }} - {{ $student->mahasiswaProfile->nim }}
                </option>
            @endforeach
        </select>
    </div>
    
    {{-- Results Section --}}
    @if ($selectedStudent)

    {{-- Format nilai --}}
    <div class="form-group">
        <label for="studentSelect">Format nilai</label>
        <select wire:model.live="selectedStudentId">
            <option value="100">0-100</option>
            <option value="10">0-10</option>
            <option value="4">0-4</option>
        </select>
    </div>
    <div class="results-section">
        
        {{-- Student Info --}}
        <div class="student-info">
            <h3>{{ $selectedStudent->name }}</h3>
            <p>
                NIM: <span>{{ $selectedStudent->mahasiswaProfile->nim }}</span> 
                | Jurusan: {{ $selectedStudent->mahasiswaProfile->jurusan ?? '-' }}
            </p>
        </div>
        
        {{-- Grades Table --}}
        <div class="grades-table">
            <h3>Detail Penilaian Dosen</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Dosen</th>
                        <th>Role</th>
                        <th>Nilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                   
                    @foreach ($selectedStudent->projects->dosenGrades as $dosenId => $finalGrade)
                        <tr>
                            <td>{{ $selectedStudent->projects->grades->firstWhere('dosen_id', $dosenId)->dosen->name }}</td>
                            <td>{{ $selectedStudent->projects->grades->firstWhere('dosen_id', $dosenId)->dosen->dosenProfile->role }}</td>
                            <td><span class="grade-value">{{ $finalGrade }}</span></td>
                            <td>
                                <button class="delete-btn" wire:click="deleteGrade({{ 1 }})">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    
                </tbody>
            </table>
        </div>
        <div class="cumulative-section">
            <h3>Nilai Kumulatif</h3>
            <div class="cumulative-grade" id="cumulativeGrade">85.33</div>
            <div class="grade-format">Format: 0-100 (Persentase)</div>
        </div>
    </div>
    @else
    {{-- Empty State --}}
    <p>Pilih mahasiswa untuk melihat hasil penilaian</p>
    @endif
    
</div>
