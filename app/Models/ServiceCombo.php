<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCombo extends Model
{
    use HasFactory;

    protected $fillable = ['service_combo','amount','quantity','tax','total_amount','gender'];

    public function serviceManagements()
    {
        return $this->belongsTo(ServiceManagement::class, 'service_id');
    }

    //  public function categories()
    // {
    //     return $this->belongsTo(Category::class, 'category_id');
    // }
    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'id';
    }

    /**
     * Get the service names from the service_combo JSON field
     */
    // public function getServiceNamesAttribute()
    // {
    //     $serviceIds = is_string($this->service_combo) ? json_decode($this->service_combo, true) : [];
    //     if (!is_array($serviceIds)) {
    //         return [];
    //     }

    //     return ServiceManagement::whereIn('id', $serviceIds)->pluck('service_name')->toArray();
    // }
}