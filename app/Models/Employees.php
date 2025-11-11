<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Employees extends Model
{
    use HasFactory, Notifiable;

    public function getRouteKeyName()
    {
        return 'employee_name';
    }

      protected $fillable = [
         'employee_name' ,'employee_id','employee_email','employee_number','password','position','employee_status','team','branch_id','joining_date','salary','gender','dob','street','city','state','pin_code','emergency_name','emergency_number','aadhar_card','schedule_id','qualification','certificate','company','experience','role','old_salary','age','address'
    ];

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'schedule_employees', 'emp_id', 'schedule_id');
    }

    public function attendances()
{
    return $this->hasMany(Attendance::class, 'emp_id');
}
 public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }


//      public function branch()
// {
//     return $this->belongsTo(Branch::class, 'branch_id');
// }

   public function branch()
        {
            return $this->belongsTo(Branch::class, 'branch_id');
        }

}
