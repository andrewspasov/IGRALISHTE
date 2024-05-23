<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\BrandCategory;
use App\Models\BrandImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class BrandController extends Controller
{

    public function show()
    {
        $activeBrands = Brand::where('is_active', 1)->get();
        $inactiveBrands = Brand::where('is_active', 0)->get();

        return view('admin.brands', compact('activeBrands', 'inactiveBrands'));
    }

    public function create()
    {
        $brandCategories = BrandCategory::all();
        return view('admin.add-brand', compact('brandCategories'));
    }

    public function store(Request $request)
    {
        Log::debug('Request data:', $request->all());

        $messages = [
            'brand_name.required' => 'Името на брендод е задожително.',
            'brand_name.unique' => 'Бренд со ова име веќе постои.',
            'brand_name.string' => 'Името на брендот мора да е од тип текст.',
            'brand_name.max' => 'Името на брендод не може да биде повеќе од 100 карактери.',
            'tags.required' => 'Мора да внесете барем една ознака.',
            'description.required' => 'Ве молиме, внесете опис за брендот.',
            'is_active.required' => 'Статусот на брендод е задожителен.',
            'brand_categories.required' => 'Ве молиме, одберете барем една категорија.',
            'images.*.image' => 'Секоја слика мора да е од тип на фајл што е за слика.',
            'images.*.max' => 'Секоја слика не може да биде повеќе од 2 MB.',
            'images' => 'Мора барем една слика.',

        ];

        $validatedData = $request->validate([
            'brand_name' => 'required|string|max:100|unique:brands,brand_name,NULL,id',
            'description' => 'required|string',
            'tags' => 'nullable|string',
            'is_active' => 'required|boolean',
            'brand_categories' => 'required|array',
            'brand_categories.*' => 'exists:brand_categories,id',
            'images.*' => 'nullable|image|max:2048',
            'images' => 'required|array|min:1',
        ], $messages);

        $tagsArray = json_decode($request->input('tags', '[]'), true);

        if (!is_array($tagsArray)) {
            $tagsArray = [];
        }

        $processedTags = array_map(function ($tag) {
            return trim($tag['value'], "#,");
        }, $tagsArray);



        $tagsJsonString = json_encode($processedTags);

        $brand = Brand::create([
            'brand_name' => $request->input('brand_name'),
            'description' => $request->input('description'),
            'tags' => $tagsJsonString,
            'is_active' => $request->input('is_active'),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = Storage::put('public/brands', $image);
                BrandImage::create([
                    'brand_id' => $brand->id,
                    'image' => $path,
                ]);
            }
        }

        $brand->brandCategories()->sync($request->brand_categories);

        return redirect()->route('admin.brands.create')->with('success', 'Успешно креиравте нов бренд!');
    }


    public function destroy($id)
    {
        try {
            $brand = Brand::findOrFail($id);

            foreach ($brand->images as $image) {
                if ($image->path) {
                    Storage::delete($image->path);
                    $image->delete();
                }
            }


            $brand->delete();

            return redirect()->route('brands')->with('success', 'Брендот е успешно избришан.');
        } catch (\Exception $e) {
            Log::error("Error deleting brand: {$e->getMessage()}");
            return back()->withErrors('An error occurred while deleting the brand.');
        }
    }





    public function edit($id)
    {
        $brand = Brand::with('brandCategories')->findOrFail($id);
        $brandCategories = BrandCategory::all();

        if (!empty ($brand->tags)) {
            $brand->tags = json_decode($brand->tags);
        }

        return view('admin.edit-brand', compact('brand', 'brandCategories'));
    }

    public function update(Request $request, $id)
    {
        Log::debug('Request data:', $request->all());

        $messages = [
            'brand_name.required' => 'Името на брендод е задожително.',
            'brand_name.unique' => 'Бренд со ова име веќе постои.',
            'brand_name.string' => 'Името на брендот мора да е од тип текст.',
            'brand_name.max' => 'Името на брендод не може да биде повеќе од 100 карактери.',
            'tags.required' => 'Мора да внесете барем една ознака.',
            'description.required' => 'Ве молиме, внесете опис за брендот.',
            'tags.*.max' => 'Секоја ознака не може да биде повеќе од 50 карактери.',
            'is_active.required' => 'Статусот на брендод е задожителен.',
            'brand_categories.required' => 'Ве молиме, одберете барем една категорија',
            'images.*.image' => 'Секоја слика мора да е од тип на фајл што е за слика',
            'images.*.max' => 'Секоја слика не може да биде повеќе од 2 MB.',
        ];

        $validatedData = $request->validate([
            'brand_name' => 'required|string|max:100|unique:brands,brand_name,' . $id . ',id',
            'description' => 'required|string',
            'tags' => 'nullable|string',
            'tags.*' => 'string|max:255',
            'is_active' => 'required|boolean',
            'brand_categories' => 'required|array',
            'brand_categories.*' => 'exists:brand_categories,id',
            'images.*' => 'nullable|image|max:2048',
        ], $messages);

        $brand = Brand::findOrFail($id);

        $tagsArray = json_decode($request->input('tags', '[]'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON decode error for tags: ' . json_last_error_msg());
            $tagsArray = [];
        }

        $tagsJson = json_encode($tagsArray);


        $brand->update([
            'brand_name' => $request->input('brand_name'),
            'description' => $request->input('description'),
            'tags' => $tagsJson,
            'is_active' => $request->input('is_active'),
        ]);


        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageId => $imageFile) {
                $existingImage = BrandImage::find($imageId);
                if ($existingImage) {
                    Storage::delete($existingImage->image);
                    $path = Storage::put('public/brands', $imageFile);
                    $existingImage->update(['image' => $path]);
                }
            }
        }

        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $imageFile) {
                $path = Storage::put('public/brands', $imageFile);
                BrandImage::create(['brand_id' => $brand->id, 'image' => $path]);
            }
        }

        $brand->brandCategories()->sync($request->input('brand_categories'));

        return redirect()->route('brands.edit', ['id' => $brand->id])->with('success', 'Брендот ви е успешно едитиран!');
    }


    public function getImage(BrandImage $brandImage)
    {
        Log::debug("Retrieved BrandImage:", $brandImage->toArray());

        $imageContent = $brandImage->image;
        return response()->make($imageContent, 200, ['Content-Type' => 'image/png']);
    }


}
