<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Po_lineitem extends Model
{
    use HasFactory;
    protected $fillable = ['product_name', 'product_code', 'quantity', 'purchaseorder_id'];



    public function purchaseorder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchaseorder_id');
    }
}
