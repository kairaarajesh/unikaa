<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Latetime extends Model
{
    use HasFactory;

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'emp_id');
    }
}
