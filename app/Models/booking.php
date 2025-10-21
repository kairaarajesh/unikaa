<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class booking extends Model
{
    use HasFactory , Notifiable;

    public function getRouteKeyName()
    {
        return 'name';
    }

    protected $fillable = [
        'name', 'phone', 'email','location','gender','service','date','time','status','artist','emp_id'
    ];

     public function employees()
    {
        return $this->belongsTo(employees::class, 'emp_id');
    }
    public function employee()
{
    return $this->belongsTo(Employees::class);
}
}