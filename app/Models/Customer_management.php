<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer_management extends Model
{
    use HasFactory;

     protected $fillable = [
        'name', 'email', 'number','notes','referral_name','referral_number','referral_email','date'
    ];
}
