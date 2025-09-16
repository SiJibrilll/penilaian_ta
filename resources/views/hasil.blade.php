<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TA Results Display</title>
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
            max-width: 800px;
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
            margin-bottom: 30px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057;
            font-size: 0.95rem;
        }
        
        .form-group select {
            width: 100%;
            max-width: 400px;
            padding: 12px 15px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
            background-color: #fff;
            transition: border-color 0.2s ease;
        }
        
        .form-group select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
        }
        
        .results-section {
            margin-top: 20px;
        }
        
        .student-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 25px;
            border-left: 4px solid #007bff;
        }
        
        .student-info h3 {
            color: #2c3e50;
            margin-bottom: 5px;
            font-size: 1.2rem;
        }
        
        .student-info p {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .grades-table {
            margin-bottom: 30px;
        }
        
        .grades-table h3 {
            font-size: 1.1rem;
            color: #495057;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .table th {
            background-color: #f8f9fa;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            font-size: 0.9rem;
        }
        
        .table td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
            color: #495057;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .grade-value {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .delete-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        
        .delete-btn:hover {
            background-color: #c82333;
        }
        
        .delete-btn:active {
            transform: translateY(1px);
        }
        
        .cumulative-section {
            background-color: #e7f3ff;
            border: 1px solid #b8daff;
            padding: 20px;
            border-radius: 6px;
            text-align: center;
        }
        
        .cumulative-section h3 {
            color: #004085;
            margin-bottom: 10px;
            font-size: 1.2rem;
        }
        
        .cumulative-grade {
            font-size: 2rem;
            font-weight: bold;
            color: #004085;
            margin-bottom: 5px;
        }
        
        .grade-format {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
            font-style: italic;
        }
        
        .finalize-btn {
            width: 100%;
            background-color: #28a745;
            color: white;
            padding: 14px 20px;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease;
            margin-top: 20px;
        }
        
        .finalize-btn:hover {
            background-color: #218838;
        }
        
        .finalize-btn:active {
            transform: translateY(1px);
        }
        
        .finalize-btn:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }

        .back-btn {
            width: 100%;
            background-color: #6c757d;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease;
            margin-top: 10px;
        }
        
        .back-btn:hover {
            background-color: #5a6268;
        }
        
        .back-btn:active {
            transform: translateY(1px);
        }
        
        .no-data {
            display: none;
        }
        
        @media (max-width: 600px) {
            .container {
                padding: 20px;
                margin: 10px;
            }
            
            .table {
                font-size: 0.85rem;
            }
            
            .table th,
            .table td {
                padding: 8px 10px;
            }
            
            .cumulative-grade {
                font-size: 1.5rem;
            }
        }
    </style>
    @livewireStyles

</head>
<body>
    <livewire:grading-results :student-id="$id" />
    @livewireScripts
</body>
</html>