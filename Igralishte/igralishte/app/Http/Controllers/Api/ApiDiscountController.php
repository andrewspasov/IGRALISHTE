<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiDiscountController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'discount_name' => 'required|string|max:255|unique:discounts,discount_name',
            'amount' => 'required|numeric|min:1|max:100',
            'discount_category_name' => 'required|exists:discount_categories,id',
            'product_ids' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $discount = new Discount();
        $discount->discount_name = $request->input('discount_name');
        $discount->amount = $request->input('amount');
        $discount->discount_category_id = $request->input('discount_category_name');
        $discount->save();

        if ($request->has('product_ids')) {
            $productIds = explode(',', $request->input('product_ids'));
            $discount->products()->attach($productIds);
        }

        return response()->json(['message' => 'Discount created successfully!', 'discount' => $discount], 201);
    }
}
