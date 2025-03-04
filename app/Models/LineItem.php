<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_id',
        'product_name',
        'product_code',
        'quantity_required',
        'quantity_delivered',
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }
}
