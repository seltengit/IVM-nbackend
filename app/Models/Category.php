<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'category_name',
        'status',
        'image',
        'parent_category',
        'created_by',
        'updated_by'
    ];

}
