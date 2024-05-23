<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{

    protected $fillable = [
        'brand_name',
        'description',
        'brand_category_id',
        'tags',
        'is_active'
    ];

    public function brandCategories()
    {
        return $this->belongsToMany(BrandCategory::class, 'brand_brand_category', 'brand_id', 'brand_category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }


    public function images()
    {
        return $this->hasMany(BrandImage::class);
    }

    
}
