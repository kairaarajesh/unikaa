<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceManagement extends Model
{
    use HasFactory;

        protected $fillable = ['service_name','amount','quantity','tax','total_amount','service_combo','gender'];

}
