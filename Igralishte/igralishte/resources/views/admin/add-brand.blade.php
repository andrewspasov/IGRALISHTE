@extends('layouts.admin-layout')

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>



<div class="container mx-auto p-6">
<form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="md:w-1/3 md:mx-auto">
            @csrf

            <div class="flex justify-between">

                <div class="flex items-center justify-between">
                    <a href="{{ route('brands') }}"><i class="fa-solid fa-arrow-left text-2xl"></i></a><span class="text-xl font-bold ml-4">Бренд</span>
                </div>
                <div class="w-30">
                    <select name="is_active" id="is_active" class="appearance-none w-full shadow-sm text-xs border-gray-300 text-black text-left font-semibold rounded-md focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500">
                    <option disabled {{ old('is_active') === null ? 'selected' : '' }}>Статус</option>
                    <option value="1" {{ old('is_active') == "1" ? 'selected' : '' }}>Активен</option>
                    <option value="0" {{ old('is_active') === "0" ? 'selected' : '' }}>Архивиран</option>
                    </select>

                </div>
            </div>
            @error('is_active')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 mt-12">
            @error('brand_name')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
                <label for="brand_name" class="block text-black text-sm font-semibold mb-2">Име на бренд:</label>
                <input type="text" name="brand_name" id="brand_name" value="{{ old('brand_name') }}" class="w-full shadow-sm sm:text-sm border-gray-300 text-gray-500 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500">
            </div>

            <div class="mb-4">
            @error('description')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
                <label for="description" class="block text-black text-sm font-semibold mb-2">Опис:</label>
                <textarea name="description" id="description" class="w-full shadow-sm sm:text-sm border-gray-300 text-gray-500 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500">{{ old('description') }}</textarea>
            </div>


            <div class="mb-4">
                @error('tags.*')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
                @error('tags')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
                <input type="hidden" id="oldTags" value="{{ old('tags', $brand->tags ?? '[]') }}">
                <input name="tags" id="tags" class="tagify-input w-full"/>
            </div>



            <div class="mb-4 image-upload-container">
            @error('images.*')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
            @error('images')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
                <label class="text-black text-sm font-semibold mb-2">Слики:</label>
                <div class="image-inputs flex justify-center mt-3">
                    @for ($i = 0; $i < 4; $i++)
                        <div class="image-input-wrapper">
                            <input type="file" name="images[]" id="image-{{ $i }}" class="image-upload-input">
                            <label for="image-{{ $i }}" class="image-upload-label">
                                <span class="image-upload-button">+</span>
                            </label>
                        </div>
                    @endfor
                </div>
            </div>

            @error('brand_categories')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
            <div class="mb-4">
                <label for="brand_categories" class="text-black text-sm font-semibold">Категорија:</label>
                <select name="brand_categories[]" id="brand_categories" multiple class="text-black w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                    @foreach ($brandCategories as $category)
                        <option value="{{ $category->id }}" 
                            {{ (collect(old('brand_categories'))->contains($category->id)) ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>



            <div class="flex justify-beteen items-center">
            <button type="submit" class="w-full md:w-1/4 text-xl text-white bg-black hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-bold rounded-xl text-md px-5 py-3.5 me-2 mb-2 dark:bg-black dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Зачувај</button>

            <a href="{{ route('brands') }}" class="text-normal underline text-black mx-5">Откажи</a>
            </div>
        </form>


</div>


<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


<script>


document.querySelectorAll('.image-upload-input').forEach(input => {
    input.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const label = input.nextElementSibling;
                label.style.backgroundImage = `url(${e.target.result})`;
                label.querySelector('.image-upload-button').textContent = '';
                input.parentElement.classList.add('has-image');
            };
            reader.readAsDataURL(file);
        }
    });
});



$(document).ready(function() {
        $('#brand_categories').select2({
            minimumResultsForSearch: Infinity,
            placeholder: "Одбери",
            allowClear: true
        });
    });

</script>



<script>
document.addEventListener('DOMContentLoaded', function() {
    var input = document.querySelector('.tagify-input');
    var oldTagsJSON = document.getElementById('oldTags').value;
    let oldTags = JSON.parse(oldTagsJSON);

    oldTags = oldTags.map(tag => typeof tag === 'string' ? { value: tag } : tag);

    var tagify = new Tagify(input, {
        dropdown: {
            maxItems: 20,
            classname: "tags-look",
            enabled: 0,
            closeOnSelect: false
        }
    });

    tagify.addTags(oldTags);

    tagify.on('add', onTagAdded)
          .on('remove', onTagRemoved);

    function onTagAdded(e){
        console.log("Tag added", e.detail);
    }

    function onTagRemoved(e){
        console.log("Tag removed", e.detail);
    }
});

</script>