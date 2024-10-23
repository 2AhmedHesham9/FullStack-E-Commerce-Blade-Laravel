<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }
    public function orderitems()
    {
        return $this->hasMany(OrderItem::class,'product_id');
    }
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_sizes')->withPivot('quantity');
    }
}
