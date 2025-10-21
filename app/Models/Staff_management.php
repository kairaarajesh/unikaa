<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff_management extends Model
{
    use HasFactory;

     protected $fillable = [
        'trainer', 'subject', 'salary','commission','trainer_email','trainer_number','branch','joining_date','gender','dob','street','city','state','pin_code','emergency_name',
        'emergency_number','aadhar_card',''
    ];

       public function Staff_attendances()
{
    return $this->hasMany(staff_attendance::class, 'staff_management__id');
}
}