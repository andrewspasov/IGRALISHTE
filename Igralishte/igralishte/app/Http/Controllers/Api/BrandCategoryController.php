<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandCategoryController extends Controller
{
    public function getCategoriesByBrand(Brand $brand)
    {
        $categories = $brand->brandCategories;

        return response()->json([
            'categories' => $categories
        ]);
    }
}
