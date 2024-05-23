<?php

namespace App\Http\Controllers;

use \Log;
use App\Models\Discount;
use App\Models\DiscountCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{


    public function index()
    {
        $discounts = Discount::all();
        return response()->json($discounts);
    }


    public function create()
    {
        $discountCategories = DiscountCategory::all();
        return view('admin.add-discount', compact('discountCategories'));
    }


    public function store(Request $request)
    {


        $messages = [
            'discount_name.required' => 'Името на попустот е задолжително.',
            'discount_name.unique' => 'Попуст со ова име веќе постои.',
            'discount_name.string' => 'Името на попустот мора да е од тип текст.',
            'discount_name.max' => 'Името на попустот не може да биде повеќе од 255 карактери.',
            'amount.required' => 'Вредноста на попустот е задолжителна.',
            'amount.numeric' => 'Вредноста на попустот мора да биде број.',
            'amount.min' => 'Попустот не може да биде помал од 0%.',
            'amount.max' => 'Попустот не може да биде поголем од 100%.',
            'discount_category_id.required' => 'Категоријата на попустот е задолжителна.',
            'discount_category_id.exists' => 'Избраната категорија не постои.',
            'product_ids.string' => 'ID на продуктите мора да бидат во текстуален формат.',
            'is_active.required' => 'Статусот на попустот е задолжителен.',
            'product_ids.*.exists' => 'Ве молиме, внесете валиден број на продукт.',
            'product_ids.*.unique' => 'Продуктот веќе има попуст.',
        ];

        $validatedData = $request->validate([
            'discount_name' => 'required|string|max:255|unique:discounts,discount_name',
            'amount' => 'required|numeric|min:0|max:100',
            'discount_category_id' => 'required|exists:discount_categories,id',
            'product_ids' => 'nullable|string',
            'is_active' => 'required|boolean',
        ], $messages);


        $productIdsInput = $request->input('product_ids');
        if (!empty ($productIdsInput)) {
            $productIds = array_map('trim', explode(',', str_replace('#', '', $productIdsInput)));

            $productsWithDiscounts = [];
            $nonExistentProducts = [];
            $invalidEntries = [];

            foreach ($productIds as $productId) {
                if (!is_numeric($productId)) {
                    $invalidEntries[] = $productId;
                    continue;
                }

                $product = Product::find($productId);
                if (!$product) {
                    $nonExistentProducts[] = $productId;
                } elseif ($product->discounts()->exists()) {
                    $productsWithDiscounts[] = $productId;
                }
            }

            $customErrors = [];
            if (!empty ($invalidEntries)) {
                $customErrors['product_ids'][] = 'Невалиден елемент: ' . implode(', ', $invalidEntries);
            }
            if (!empty ($nonExistentProducts)) {
                $customErrors['product_ids'][] = 'Продукти со овие бројки не постојат: ' . implode(', ', $nonExistentProducts);
            }
            if (!empty ($productsWithDiscounts)) {
                $customErrors['product_ids'][] = 'Продукти кои веќе имаат попуст: ' . implode(', ', $productsWithDiscounts);
            }

            if (!empty ($customErrors)) {
                return redirect()->back()->withErrors(['product_ids' => implode(' ', $customErrors['product_ids'])])->withInput();
            }
        }





        $discount = Discount::create([
            'discount_name' => $request->input('discount_name'),
            'amount' => $request->input('amount'),
            'discount_category_id' => $request->input('discount_category_id'),
            'is_active' => $request->input('is_active'),
        ]);


        $productIds = explode(',', $request->input('product_ids'));
        $productIds = array_map(function ($id) {
            return trim($id, " \t\n\r\0\x0B#");
        }, $productIds);
        $productIds = array_filter($productIds, 'is_numeric');
        $productIds = array_unique($productIds);

        $discount->products()->sync($productIds);

        return redirect()->route('admin.discount.create')->with('success', 'Попустот успешно е додаден.');
    }


    public function show()
    {
        $activeDiscounts = Discount::where('is_active', 1)->get();
        $inactiveDiscounts = Discount::where('is_active', 0)->get();

        return view('admin.discounts', compact('activeDiscounts', 'inactiveDiscounts'));
    }


    public function edit($id)
    {
        $discount = Discount::with(['discountCategory', 'products'])->findOrFail($id);
        $discountCategories = DiscountCategory::all();
        $products = Product::all();
        return view('admin.edit-discount', compact('discount', 'discountCategories', 'products'));
    }



    public function update(Request $request, $id)
    {
        $messages = [
            'discount_name.required' => 'Името на попустот е задолжително.',
            'discount_name.unique' => 'Попуст со ова име веќе постои.',
            'discount_name.string' => 'Името на попустот мора да е од тип текст.',
            'discount_name.max' => 'Името на попустот не може да биде повеќе од 255 карактери.',
            'amount.required' => 'Вредноста на попустот е задолжителна.',
            'amount.numeric' => 'Вредноста на попустот мора да биде број.',
            'amount.min' => 'Попустот не може да биде помал од 1%.',
            'amount.max' => 'Попустот не може да биде поголем од 100%.',
            'discount_category_id.required' => 'Категоријата на попустот е задолжителна.',
            'discount_category_id.exists' => 'Избраната категорија не постои.',
            'is_active.required' => 'Статусот на попустот е задолжителен.',
            'product_ids.regex' => 'Невалиден внес. Дозволени се само броеви, запирки и знакот #.',

        ];

        $validatedData = $request->validate([
            'discount_name' => 'required|string|max:255|unique:discounts,discount_name,' . $id,
            'amount' => 'required|numeric|min:1|max:100',
            'discount_category_id' => 'required|exists:discount_categories,id',
            'is_active' => 'required|boolean',
            'product_ids' => ['nullable', 'regex:/^[\d#,\s]*$/'],
        ], $messages);

        $productIdsInput = $request->input('product_ids', '');
        $productIds = array_filter(array_map('trim', explode(',', str_replace('#', '', $productIdsInput))), 'is_numeric');

        $existingProductIds = \App\Models\Product::whereIn('id', $productIds)->pluck('id')->toArray();
        $invalidProductIds = array_diff($productIds, $existingProductIds);

        $productsWithOtherDiscounts = DB::table('product_discount')
            ->whereIn('product_id', $existingProductIds)
            ->where('discount_id', '!=', $id)
            ->pluck('product_id')
            ->toArray();

        $errors = [];
        
        if (!empty ($invalidProductIds)) {
            $errors['product_ids'] = 'Продукти со ID не постојат: ' . implode(', ', $invalidProductIds);
        }
        if (!empty ($productsWithOtherDiscounts)) {
            $existingErrorMsg = $errors['product_ids'] ?? '';
            $errors['product_ids'] = $existingErrorMsg . (empty ($existingErrorMsg) ? '' : ' ') . 'Овие продукти веќе имаат попуст: ' . implode(', ', $productsWithOtherDiscounts);
        }

        if (!empty ($errors)) {
            return redirect()->back()->withErrors($errors)->withInput();
        }

        $discount = Discount::findOrFail($id);
        $discount->update([
            'discount_name' => $request->input('discount_name'),
            'amount' => $request->input('amount'),
            'discount_category_id' => $request->input('discount_category_id'),
            'is_active' => $request->input('is_active'),
        ]);

        $discount->products()->sync($existingProductIds);

        return redirect()->route('discounts.edit', ['id' => $discount->id])->with('success', 'Попустот е успешно апдејтиран.');
    }




    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        // Detach all associated products from this discount
        $discount->products()->detach();
        
        $discount->delete();
    
        return redirect()->route('discounts')->with('success', 'Попустот е успешно избришан.');
    }
    

}
