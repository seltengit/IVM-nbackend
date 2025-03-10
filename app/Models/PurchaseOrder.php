<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_number',
        'name'
    ];

    public function polineItems()
    {
        return $this->hasMany(Po_lineitem::class, 'purchaseorder_id');
    }
}
