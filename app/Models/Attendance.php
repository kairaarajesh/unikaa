<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Attendance extends Model
{
    use HasFactory,Notifiable;

      public function getRouteKeyName()
    {
        return 'name';
    }

     public function employees()
    {
        return $this->belongsTo(Employees::class);
    }
}
