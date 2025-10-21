<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory,Notifiable;

    public function getRouteKeyName()
    {
        return 'name';
    }

    protected $fillable = [
        'customer_id', 'name', 'email', 'number', 'place', 'date', 'referral_name',
        'referral_number', 'referral_email', 'employee_id', 'employee_details',
        'branch_id', 'gender', 'payment','membership_card'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Resolve branch by matching customer's branch string to branches.name.
     */
    // public function branchRecord(): BelongsTo
    // {
    //     return $this->belongsTo(Branch::class, 'branch', 'name');
    // }

    // public function branch()
    // {
    //     return $this->belongsTo(Branch::class, 'branch', 'name');
    // }

            public function branch()
        {
            return $this->belongsTo(Branch::class, 'branch_id');
        }

        public function customer()
        {
            return $this->belongsTo(Customer::class, 'customer_id');
        }

        public function invoices()
        {
            return $this->hasMany(Invoice::class);
        }
}