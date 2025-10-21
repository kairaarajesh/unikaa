<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

     protected $fillable = [
        'emp_id' ,'service','date','position',
    ];


     public function employees()
    {
        return $this->belongsTo(employees::class, 'emp_id');
    }
}
