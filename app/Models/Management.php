<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\Branch;

class management extends Model
{
    use HasFactory, Notifiable;

    public function getRouteKeyName()
    {
        return 'product_name';
    }
    protected $fillable = [
        'product_name', 'product_code', 'Quantity','price','branch_id','date','category_id','place'
    ];

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function getBalanceAttribute()
    {
        $unsold = $this->purchase ? $this->purchase->quantity : 0;
        return ($this->quantity * 10) - $unsold;
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

}
