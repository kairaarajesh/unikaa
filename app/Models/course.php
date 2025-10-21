<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

       protected $fillable = [
        'name','duration','fees','start_time','end_time','max_student','staff_management_id','batch','type','course'
    ];

    public function staff_managements()
    {
        return $this->belongsTo(Staff_management::class, 'staff_management_id');
    }
}