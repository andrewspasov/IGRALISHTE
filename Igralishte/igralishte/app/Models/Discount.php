<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{

    protected $fillable = [
        'discount_name',
        'amount',
        'discount_category_id',
        'product_ids',
        'is_active'
    ];

    public function discountCategory()
    {
        return $this->belongsTo(DiscountCategory::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_discount', 'discount_id', 'product_id');
    }

    public function products2()
    {
        return $this->belongsToMany(Product::class, 'product_discount');
    }
    
    public function getProductIdsAttribute($value)
    {
        return explode(',', $value);
    }

    public function setProductIdsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['product_ids'] = implode(',', $value);
        }
    }


}
