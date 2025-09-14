<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        "nilai",
        "format",
        "dosen_id",
        "mahasiswa_id"
    ];

    function mahasiswa() {
        return $this->belongsTo(User::class, 'mahasiswa_id', 'id');
    }

    function dosen() {
        return $this->belongsTo(User::class, 'dosen_id', 'id');
    }
}
