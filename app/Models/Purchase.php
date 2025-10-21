<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class purchase extends Model
{
    use HasFactory, Notifiable;

    public function getRouteKeyName()
    {
        return 'customer_name';
    }
     protected $fillable = [
        'customer_name','customer_number','Quantity','price','branch','management_id','product_code','payment','total_amount','employee_id','employee_details','tax','total_calculation','discount'
    ];

    public function management()
    {
        return $this->belongsTo(Management::class, 'management_id');
    }

     public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }
}