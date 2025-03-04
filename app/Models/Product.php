<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hsincode',
        'category',
        'sub_category',
        'description',
        'brand',
        'design',
        'price',
        'stocks',
        'unit',
        'varient',
        'status',
        'test1',
        'test2',
    ];
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($product) {
            if ($product->stocks == 0) {
                $product->status = '0'; // Set status to '0' if stocks are 0
            } elseif ($product->stocks > 0) {
                $product->status = '1'; // Set status to '1' if stocks are greater than 0
            }
        });
    }
}
