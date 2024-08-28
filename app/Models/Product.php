<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'price',
        'discount',
        'photo',
        'category_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */

     public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }


public function getDiscountedPriceAttribute()
{
    if ($this->discount) {
        $discountedPrice = $this->price * (1 - $this->discount / 100);
        return number_format($discountedPrice / 100, 2);
    }
    return number_format($this->price / 100, 2);
}

public function cartProducts()
{
    return $this->hasMany(CartProduct::class, 'product_id');
}



}
