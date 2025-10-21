<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Student_attendance extends Model
{
    use HasFactory,Notifiable;

  public function getRouteKeyName()
    {
        return 'name';
    }
     public function student()
    {
        return $this->belongsTo(Student::class,'student_id');
    }
}