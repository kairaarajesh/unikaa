<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Staff_attendance extends Model
{
 use HasFactory,Notifiable;

  public function getRouteKeyName()
    {
        return 'name';
    }
     public function staff_management()
    {
        return $this->belongsTo(Staff_management::class,'staff_management__id');
    }
}