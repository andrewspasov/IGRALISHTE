<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandCategory extends Model
{
    protected $fillable = ['category_name'];
    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'brand_brand_category', 'brand_category_id', 'brand_id');
    }
}