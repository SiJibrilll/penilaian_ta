<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GradeController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('grades', GradeController::class);
