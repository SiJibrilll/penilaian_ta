<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TA Grading System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
            color: #333;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #6c757d;
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057;
            font-size: 0.95rem;
        }
        
        .form-group select,
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
            background-color: #fff;
            transition: border-color 0.2s ease;
        }
        
        .form-group select:focus,
        .form-group input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
        }
        
        .grades-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 25px;
        }
        
        .grades-section h3 {
            font-size: 1.1rem;
            color: #495057;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .grade-row {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            gap: 15px;
        }
        
        .grade-row:last-child {
            margin-bottom: 0;
        }
        
        .grade-label {
            min-width: 180px;
            font-weight: 500;
            color: #495057;
            font-size: 0.9rem;
        }
        
        .grade-input {
            flex: 1;
            max-width: 120px;
        }
        
        .grade-input input {
            margin: 0;
            text-align: center;
        }
        
        .submit-btn {
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 14px 20px;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        
        .submit-btn:hover {
            background-color: #0056b3;
        }
        
        .submit-btn:active {
            transform: translateY(1px);
        }
        
        .format-info {
            background-color: #e7f3ff;
            border: 1px solid #b8daff;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            color: #004085;
        }
        
        @media (max-width: 600px) {
            .container {
                padding: 20px;
                margin: 10px;
            }
            
            .grade-row {
                flex-direction: column;
                align-items: stretch;
                gap: 5px;
            }
            
            .grade-label {
                min-width: auto;
            }
            
            .grade-input {
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>TA Grading System</h1>
            <p>Sistem Penilaian Tugas Akhir</p>
        </div>
        
        <form id="gradingForm" method="POST" action="/grades">
            @csrf
            
            <div class="form-group">
                <label for="studentSelect">Pilih Mahasiswa</label>
                <select id="studentSelect" name="student" required>
                    <option value="">-- Pilih Mahasiswa --</option>
                    @foreach($mahasiswa as $pilihan)
                        <option value="{{ $pilihan['id'] }}" {{ old('student') == $pilihan['id'] ? 'selected' : '' }}>
                            {{ $pilihan['name'] }}
                        </option>
                    @endforeach
                </select>
                @error('student')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="dosenSelect">Pilih Dosen Penilai</label>
                <select id="dosenSelect" name="dosen" required>
                    <option value="">-- Pilih Dosen --</option>
                    @foreach($dosen as $d)
                        <option value="{{ $d['id'] }}" {{ old('dosen') == $d['id'] ? 'selected' : '' }}>
                            {{ $d['name'] }}
                        </option>
                    @endforeach
                </select>
                @error('dosen')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="formatSelect">Pilih format penilaian</label>
                <select id="formatSelect" name="format" required>
                    <option value="">-- Pilih format --</option>
                    <option value="format1" {{ old('format') == 'format1' ? 'selected' : '' }}>0-4</option>
                    <option value="format2" {{ old('format') == 'format2' ? 'selected' : '' }}>0-10</option>
                    <option value="format3" {{ old('format') == 'format3' ? 'selected' : '' }}>0-100</option>
                </select>
                @error('format')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="grades-section">
                <h3>Komponen Penilaian</h3>
                
                @foreach($penilaian as $nilai)
                    <div class="grade-row">
                        <div class="grade-label">Nilai {{ $nilai['name'] }}:</div>
                        <div class="grade-input">
                            <input type="number" name="{{ $nilai['id'] }}" value="{{ old($nilai['id']) }}">
                        </div>
                    </div>
                     @error($nilai['id'])
                        <div class="error">{{ $message }}</div>
                        <br>
                    @enderror
                @endforeach
            </div>
            
            <button type="submit" class="submit-btn">Submit Nilai</button>
        </form>

    </div>
</body>
</html>