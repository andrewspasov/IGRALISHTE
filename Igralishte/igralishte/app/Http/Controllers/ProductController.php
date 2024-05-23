<?php

namespace App\Http\Controllers;

use \Log;
use App\Models\Brand;
use App\Models\BrandCategory;
use App\Models\Color;
use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function create()
    {
        $brands = Brand::all();
        $sizes = Size::all();
        $colors = Color::all();

        return view('admin.add-product', compact('brands', 'sizes', 'colors'));
    }



    public function store(Request $request)
    {
        $messages = [
            'product_name.required' => 'Името на продуктот е задолжително.',
            'product_name.unique' => 'Продукт со ова име веќе постои.',
            'product_name.string' => 'Името на продуктот мора да е од тип текст.',
            'product_name.max' => 'Името на продуктот не може да биде повеќе од 255 карактери.',
            'tags.required' => 'Мора да внесете барем една ознака.',
            'description.required' => 'Ве молиме, внесете опис за продуктот.',
            'tags.*.max' => 'Секоја ознака не може да биде повеќе од 50 карактери.',
            'is_active.required' => 'Статусот на продуктот е задолжителен.',
            'brand_id.required' => 'Ве молиме, одберете бренд.',
            'brand_category_id.required' => 'Ве молиме, одберете категорија.',
            'images.*.image' => 'Секоја слика мора да е од тип на фајл што е за слика.',
            'images.*.max' => 'Секоја слика не може да биде повеќе од 2 MB.',
            'images' => 'Барем една слика за продуктот е задоллжително.',
            'price.required' => 'Ве молиме, внесете цена на продуктот.',
            'price.integer' => 'Цената мора да биде број.'
        ];

        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255|unique:products,product_name,NULL,id',
            'description' => 'required',
            'price' => 'required|integer',
            'tags' => 'nullable|string',
            'brand_id' => 'required|exists:brands,id',
            'brand_category_id' => 'required|exists:brand_categories,id',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'how_to_use' => 'nullable|string',
            'desc_for_size' => 'nullable|string',
            'is_active' => 'required|boolean',
            'discount_id' => 'nullable',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $product = new Product();
        $product->product_name = $request->product_name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->how_to_use = $request->how_to_use;
        $product->desc_for_size = $request->desc_for_size;
        $product->brand_id = $request->brand_id;
        $product->brand_category_id = $request->brand_category_id;
        $product->tags = $request->tags;
        $product->is_active = $request->is_active;

        $tagsArray = json_decode($request->input('tags', '[]'), true);

        if (is_array($tagsArray)) {
            $tags = array_map(function ($tag) {
                return $tag['value'] ?? '';
            }, $tagsArray);

            $tags = array_filter($tags, function ($tag) {
                return !empty ($tag);
            });
        } else {
            \Log::error('Tags input is not a valid JSON array.', ['input' => $request->input('tags')]);
            $tags = [];
        }

        $product->tags = json_encode(array_values($tags));
        $product->save();



        if ($request->has('combinations')) {
            $combinations = json_decode($request->input('combinations'), true);
            foreach ($combinations as $combo) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size_id' => $combo['sizeId'],
                    'color_id' => $combo['colorId'],
                    'quantity' => $combo['quantity'],
                ]);
            }
        }


        if ($request->hasfile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $filename = Str::slug($request->product_name) . '_' . time() . '_' . $key . '.' . $image->getClientOriginalExtension();
                Log::debug('Generated filename for image upload', ['filename' => $filename]);

                $path = $image->storeAs('products', $filename, 'public');
                Log::debug('Image stored at path', ['path' => $path]);

                if (!$path) {
                    Log::error("Image failed to upload", ['filename' => $filename]);
                    continue;
                }

                $productImage = new ProductImage([
                    'product_id' => $product->id,
                    'image' => $path,
                ]);

                try {
                    $productImage->save();
                    Log::info('Product image saved successfully', ['image' => $productImage]);
                } catch (\Exception $e) {
                    Log::error('Failed to save product image', ['error' => $e->getMessage(), 'image' => $path]);
                }
            }
        }




        Log::debug('Request data', $request->all());
        if ($request->has('selectedDiscountId') && !empty ($request->selectedDiscountId)) {


            try {
                $product->discounts()->attach($request->selectedDiscountId);
                Log::debug('Attaching discount', ['discountId' => $request->selectedDiscountId]);

                Log::debug('Discount attached');
            } catch (\Exception $e) {
                Log::error('Error attaching discount: ' . $e->getMessage());
            }
        }
        return redirect()->route('create')->with('success', 'Продуктот е успешно креиран!');
    }



    public function show($id)
    {
        $product = Product::with(['brand', 'brandCategory', 'productVariants', 'productVariants.size', 'productVariants.color', 'productImages'])->findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function index(Request $request)
    {
        $viewType = $request->get('viewType', 'small');
        $searchQuery = $request->get('query', '');
        $searchType = $request->get('searchType', 'productName');

        $productsQuery = Product::with(['brand', 'brandCategory', 'productVariants.size', 'productVariants.color', 'productImages']);

        if (!empty ($searchQuery)) {
            switch ($searchType) {
                case 'brandName':
                    $productsQuery->whereHas('brand', function ($query) use ($searchQuery) {
                        $query->where('brand_name', 'like', "%{$searchQuery}%");
                    });
                    break;
                case 'categoryName':
                    $productsQuery->whereHas('brandCategory', function ($query) use ($searchQuery) {
                        $query->where('category_name', 'like', "%{$searchQuery}%");
                    });
                    break;
                case 'productName':
                default:
                    $productsQuery->where('product_name', 'like', "%{$searchQuery}%");
                    break;
            }
        }

        $products = $productsQuery->paginate($viewType === 'small' ? 9 : 3)->withQueryString();

        return view('admin.products', compact('products', 'viewType', 'searchQuery', 'searchType'));
    }




    public function showDetails($id)
    {
        $product = Product::with(['brand', 'brandCategory', 'productVariants.size', 'productVariants.color', 'productImages', 'discounts'])->findOrFail($id);

        return view('admin.product_details', compact('product'));
    }

    public function edit($productId)
    {
        $product = Product::with('discounts2', 'productImages')->findOrFail($productId);

        if (!empty ($product->tags)) {
            $product->tags = json_decode($product->tags);
        }

        $discounts = Discount::all();

        $sizes = Size::all();
        $colors = Color::all();
        $brands = Brand::all();
        $brandCategories = BrandCategory::all();

        $currentDiscount = $product->discounts->first();

        return view('admin.edit-product', compact('product', 'sizes', 'colors', 'brands', 'brandCategories', 'currentDiscount', 'discounts'));
    }


    public function resetVariants(Request $request)
    {
        Log::info('Resetting variants for product: ' . $request->input('product_id'));

        $productId = $request->input('product_id');
        $product = Product::findOrFail($productId);
        $product->productVariants()->delete();

        Log::info('Variants reset successfully.');

        return response()->json(['success' => true]);
    }


    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);


        $messages = [
            'product_name.required' => 'Името на продуктот е задолжително.',
            'product_name.unique' => 'Продукт со ова име веќе постои.',
            'product_name.string' => 'Името на продуктот мора да е од тип текст.',
            'product_name.max' => 'Името на продуктот не може да биде повеќе од 255 карактери.',
            'tags.required' => 'Мора да внесете барем една ознака.',
            'description.required' => 'Ве молиме, внесете опис за продуктот.',
            'tags.*.max' => 'Секоја ознака не може да биде повеќе од 50 карактери.',
            'is_active.required' => 'Статусот на продуктот е задолжителен.',
            'brand_id.required' => 'Ве молиме, одберете бренд.',
            'brand_category_id.required' => 'Ве молиме, одберете категорија.',
            'images.*.image' => 'Секоја слика мора да е од тип на фајл што е за слика.',
            'images.*.max' => 'Секоја слика не може да биде повеќе од 2 MB.',
            'price.required' => 'Ве молиме, внесете цена на продуктот.',
            'price.numeric' => 'Цената мора да биде број.',
            // 'images' => 'Барем една слика за продуктот е задоллжително.',

        ];

        $rules = [
            'product_name' => 'required|string|max:255|unique:products,product_name,' . $id,
            'description' => 'required',
            'price' => 'required|numeric',
            'tags' => 'nullable|string',
            'brand_id' => 'required|exists:brands,id',
            'brand_category_id' => 'required|exists:brand_categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'how_to_use' => 'nullable|string',
            'desc_for_size' => 'nullable|string',
            'is_active' => 'required|boolean',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'images' => 'required|array|min:1',

        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $product->update([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'price' => $request->price,
            'how_to_use' => $request->how_to_use,
            'desc_for_size' => $request->desc_for_size,
            'brand_id' => $request->brand_id,
            'brand_category_id' => $request->brand_category_id,
            'is_active' => $request->is_active,
        ]);



        $existingTags = $product->tags ? json_decode($product->tags, true) : [];

        $tagsToAdd = array_filter(explode(',', $request->input('tagsToAdd', '')), function ($tag) {
            return !empty (trim($tag));
        });
        $tagsToRemove = array_filter(explode(',', $request->input('tagsToRemove', '')), function ($tag) {
            return !empty (trim($tag));
        });

        foreach ($tagsToAdd as $tag) {
            if (!in_array($tag, $existingTags)) {
                $existingTags[] = $tag;
            }
        }

        $existingTags = array_filter($existingTags, function ($tag) use ($tagsToRemove) {
            return !in_array($tag, $tagsToRemove);
        });

        $product->tags = json_encode(array_values($existingTags));
        $product->save();




        $combinationsJson = $request->input('combinations', '[]');
        $combinations = json_decode($combinationsJson, true);

        if (!is_array($combinations)) {
            Log::error("Invalid combinations input", ['input' => $combinationsJson]);
            $combinations = [];
        }

        foreach ($combinations as $combination) {
            $variant = $product->productVariants()->updateOrCreate([
                'size_id' => $combination['sizeId'],
                'color_id' => $combination['colorId'],
            ], [
                'quantity' => $combination['quantity'],
            ]);
        }



        if ($request->hasfile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $filename = Str::slug($product->name) . '_' . time() . '.' . $image->getClientOriginalExtension();
                $filePath = $image->storeAs('products', $filename, 'public');

                $productImage = new ProductImage([
                    'product_id' => $product->id,
                    'image' => $filePath,
                ]);
                $productImage->save();
            }
        }

        if ($request->filled('removed_images')) {
            $imagesToRemove = $request->removed_images;
            foreach ($imagesToRemove as $imageId) {
                $image = ProductImage::find($imageId);
                if ($image) {
                    Storage::delete('public/' . $image->image);
                    $image->delete();
                }
            }
        }

        $discountId = $request->input('discount_id');

        if (empty ($discountId)) {
            $product->discounts()->detach();
        } else {
            $discountIds = is_array($discountId) ? $discountId : [$discountId];
            $product->discounts()->sync($discountIds);
        }


        $product->save();

        return redirect()->route('products.edit', ['product' => $product->id])->with('success', 'Продуктот е успешно едитиран!');
    }





    public function getImage($imageId)
    {
        $image = ProductImage::findOrFail($imageId);

        $imagePath = Storage::disk('public')->path($image->image);

        if (!Storage::disk('public')->exists($image->image)) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        $imageContent = Storage::disk('public')->get($image->image);
        $mimeType = Storage::disk('public')->mimeType($image->image);

        return response($imageContent, 200)->header('Content-Type', $mimeType);
    }


    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        ProductVariant::where('product_id', $id)->delete();
        $productImages = ProductImage::where('product_id', $id)->get();

        foreach ($productImages as $image) {
            Storage::delete($image->image);
            $image->delete();
        }

        $product->delete();
        return redirect()->route('products')->with('success', 'Продуктот е успешно избришан.');
    }



    public function checkVariant(Request $request)
    {
        $exists = ProductVariant::where('product_id', $request->product_id)
            ->where('size_id', $request->size_id)
            ->where('color_id', $request->color_id)
            ->exists();

        return response()->json(['exists' => $exists]);
    }

}