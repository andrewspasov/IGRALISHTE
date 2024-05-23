<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    protected $fillable = [
        'product_name',
        'description',
        'price',
        // 'how_many',
        'how_to_use',
        'desc_for_size',
        'tags',
        'images',
        'brand_id',
        'brand_category_id',
        // 'color_id',
        // 'size_id',
        'discount_id',
        'is_active'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function brandCategory()
    {
        return $this->belongsTo(BrandCategory::class);
    }



    public function brandCategories()
    {
        return $this->hasMany(BrandCategory::class);
    }


    public function size()
    {
        return $this->belongsTo(Size::class);
    }


    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }


    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'product_discount', 'product_id', 'discount_id');
    }


    public function discounts2()
    {
        return $this->belongsToMany(Discount::class, 'product_discount');
    }


    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }



    public function getImagesAttribute($value)
    {
        return explode(',', $value);
    }

    public function setImagesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['image'] = implode(',', $value);
        }
    }


    public function sizes()
    {
        return $this->hasManyThrough(
            Size::class,
            ProductVariant::class,
            'product_id',
            'id',
            'id',
            'size_id'
        )->distinct();
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_variants', 'product_id', 'color_id')
            ->distinct();
    }

    public function getColorsAttribute()
    {
        return $this->productVariants->load('color')->pluck('color')->unique('id');
    }




    public function getDiscountedPriceAttribute()
    {
        $discount = $this->discounts->first();

        if ($discount) {
            $discountedPrice = $this->price - ($this->price * ($discount->amount / 100));
            return round($discountedPrice, 2);
        }

        return $this->price;
    }


}
