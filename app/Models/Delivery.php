<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_address',
        'customer_phone',
        'driver_name',
        'driver_vehicle_no',
        'driver_phone',
        'pending',
        'reason'

    ];

    public function lineItems()
    {
        return $this->hasMany(LineItem::class);
    }
}
