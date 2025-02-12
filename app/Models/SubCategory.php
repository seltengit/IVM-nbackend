<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'description',
        'size',
        'status',
        'image',
        'parent_category',
        'created_by',
        'updated_by'
    ];

    protected $table = 'sub_categories'; // Explicitly set the correct table name

    // Define Inverse One-to-Many Relationship

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
