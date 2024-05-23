<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'image',
    ];


    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
