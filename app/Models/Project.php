<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    function grades() {
        return $this->hasMany(Grade::class, 'project_id');
    }
}
