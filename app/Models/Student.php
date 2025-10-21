<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

     protected $fillable = [
        'student_name','student_id','email','number','gender','dob','joining_date','street','city','state','pin_code','emergency_name','emergency_number','aadhar_card','fees_status','payment_history','batch_timing','staff_management_id','course_id'
    ];

     public function staff_managements()
    {
        return $this->belongsTo(Staff_management::class, 'staff_management_id');
    }

     public function courses()
    {
        return $this->belongsTo(course::class, 'course_id');
    }
    
       public function student_attendances()
{
    return $this->hasMany(Student_attendance::class, 'student_id');
}
}