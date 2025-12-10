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
        'customer_id', 'name', 'email', 'login_email', 'password', 'number', 'place', 'date', 'referral_name',
        'referral_number', 'referral_email', 'employee_id', 'employee_details',
        'branch_id', 'gender', 'payment', 'membership_card', 'cash_amount', 'cash_refund_amount', 'cash_total_amount'
    ];

    protected $casts = [
        'date' => 'date',
        'payment' => 'array', // Automatically converts JSON string to array when reading, array to JSON when saving
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

         public function employee()
        {
            return $this->belongsTo(Employees::class, 'employee_id');
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
