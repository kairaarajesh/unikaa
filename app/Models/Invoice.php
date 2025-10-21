<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'invoice_number', 'date', 'amount', 'tax', 'total_amount',
        'service_items', 'purchase_items', 'purchase_total_amount', 'subtotal',
        'service_tax_amount', 'service_total_calculation', 'payment_method',
        'branch_id', 'employee_id', 'employee_details'
    ];

    protected $casts = [
        'service_items' => 'array',
        'purchase_items' => 'array',
        'date' => 'datetime',
        'amount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'purchase_total_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'service_tax_amount' => 'decimal:2',
        'service_total_calculation' => 'decimal:2',
    ];

    // Relationship with Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relationship with Branch
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // Relationship with Employee
    public function employee()
    {
        return $this->belongsTo(Employees::class);
    }

    // Generate unique invoice number
    public static function generateInvoiceNumber()
    {
        $lastInvoice = self::orderBy('id', 'desc')->first();
        $nextNumber = $lastInvoice ? $lastInvoice->id + 1 : 1;
        return 'INV' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }
}