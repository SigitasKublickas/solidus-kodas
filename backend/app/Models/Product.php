<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "desc",
        "price",
        "delivery_time",
        "stock",
        "img_url",
        "category_id",
        "brand",
        "model",
        "condition",
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}
