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
        <label for="formatSelect">Format nilai</label>
        <select wire:model.live="format">
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
                        <th>Role</th>
                        <th>Nama Dosen</th>
                        <th>Nilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                   
                    @foreach ($dosenGrades as $grade)
                        <tr>
                            <td>{{ $grade['role'] }}</td>
                            <td>{{ $grade['dosen']->name ?? $grade['dosen'] }}</td>
                            <td><span class="grade-value">{{ $grade['grade']}}</span></td>
                            <td>
                                @if($grade['dosen'] != '-')
                                    <button class="delete-btn" wire:click="deleteGrade({{$grade['dosen']->id}})">
                                        Hapus
                                    </button>
                                @else
                                    <div>-</div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    
                </tbody>
            </table>
        </div>
        <div class="cumulative-section">
            <h3>Nilai Kumulatif</h3>
            @if($cummulativeGrade)
                <div class="cumulative-grade" id="cumulativeGrade">{{$cummulativeGrade}}</div>
            @else
                <div class="grade-format">Data belum lengkap</div>
            @endif
        </div>

        @if($cummulativeGrade)
        <button type="button" class="finalize-btn" id="finalizeBtn" onclick="finalizeGrades()">
            Finalisasi Nilai
        </button>
        @endif
        <button type="button" class="back-btn" id="back-btn" onclick="window.location='{{ url("/grades/create") }}'">
            Kembali
        </button>
    </div>
    @else
    {{-- Empty State --}}
    <p>Pilih mahasiswa untuk melihat hasil penilaian</p>
    @endif
    
</div>
