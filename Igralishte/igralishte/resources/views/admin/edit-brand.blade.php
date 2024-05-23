@extends('layouts.admin-layout')


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>

<div class="container mx-auto p-6">

    <form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-4 mb-8 md:w-1/3 md:mx-auto">
    
        @csrf
        @method('PUT')

        <div class="flex justify-between mb-5">

<div class="flex items-center justify-between">
    <a href="{{ route('brands') }}"><i class="fa-solid fa-arrow-left text-2xl"></i></a><span class="text-xs md:text-2xl font-bold ml-4">Едитирај Бренд</span>
</div>

<div class="w-30">
    <select name="is_active" id="is_active" class="appearance-none w-full shadow-sm text-xs border-gray-300 text-black text-left font-semibold rounded-md focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 truncate">
                <option value="1" {{ $brand->is_active ? 'selected' : '' }}>Активен</option>
                <option value="0" {{ !$brand->is_active ? 'selected' : '' }}>Арихивиран</option>
            </select>
</div>
</div>
        
        <div class="mb-4">
        @error('is_active')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
        </div>

        <div class="mb-4">
        @error('brand_name')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
            <label for="brand_name" class="block text-black font-semibold text-sm  mb-2">Име на бренд:</label>
            <input type="text" name="brand_name" id="brand_name" value="{{ $brand->brand_name }}" class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-500 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
        @error('description')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
            <label for="description" class="block text-black font-semibold text-sm mb-2">Опис:</label>
            <textarea name="description" id="description" class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-500 leading-tight focus:outline-none focus:shadow-outline">{{ $brand->description }}</textarea>
        </div>

        <div class="mb-4">
            @error('tags')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
            <label for="tags" class="block text-sm text-black font-semibold mb-2">Ознаки:</label>
            <input type="text" id="tags" name="tags" value="" class="tags-input w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
        </div>

        <div class="mb-4">
        @error('brand_categories')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
            <label for="brand_categories" class="block text-sm text-black font-semibold mb-2">Категории на бренд:</label>
            <select multiple name="brand_categories[]" id="brand_categories2" class="w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:bg-white focus:border-gray-500">
                @foreach($brandCategories as $category)
                    <option value="{{ $category->id }}" {{ $brand->brandCategories->contains('id', $category->id) ? 'selected' : '' }}>{{ $category->category_name }}</option>
                @endforeach
            </select>
        </div>


        <div class="mb-4 image-upload-container-edit">
            <label for="images" class="block text-gray-700 text-sm font-bold mb-2">Слики:</label>
            <div class="flex justify-center items-center space-x-4 mb-4">
                <!-- Existing images -->
                @foreach($brand->images as $index => $image)
                    <div class="image-input-wrapper-edit relative" style="width: 60px; height: 60px;">
                        <img src="{{ Storage::url($image->image) }}" alt="Brand Image" class="absolute inset-0 object-cover w-full h-full rounded-md cursor-pointer">
                        <input type="file" name="images[{{ $image->id }}]" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                @endforeach
                <!-- Add new image slots -->
                @for ($i = count($brand->images); $i < 4; $i++)
                    <div class="image-input-wrapper-edit relative" style="width: 60px; height: 60px;">
                        <div class="absolute inset-0 flex items-center justify-center bg-gray-200 rounded-md cursor-pointer">
                            <span class="text-xl font-bold text-gray-700">+</span>
                        </div>
                        <input type="file" name="new_images[]" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                @endfor
            </div>
        </div>




        <div class="flex justify-beteen items-center">
            <button type="submit" class="w-full md:w-1/4 text-xl text-white bg-black hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-bold rounded-xl text-md px-5 py-3.5 me-2 mb-2 dark:bg-black dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Зачувај</button>

            <a href="{{ route('brands') }}" class="text-normal underline text-black mx-5">Откажи</a>
            </div>    </form>

    @if(session('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    {{ session('success') }}
                </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#brand_categories2').select2({
            minimumResultsForSearch: Infinity,
            allowClear: true
        });
    });



document.addEventListener('DOMContentLoaded', (event) => {
    const imageContainers = document.querySelectorAll('.image-input-wrapper-edit');

    imageContainers.forEach(container => {
        const fileInput = container.querySelector('input[type="file"]');
        const image = container.querySelector('img');

        if (image) {
            image.addEventListener('click', () => fileInput.click());
        }

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (!image) {
                        const newImage = document.createElement('img');
                        newImage.setAttribute('class', 'absolute inset-0 object-cover w-full h-full rounded-md');
                        container.prepend(newImage);
                        newImage.src = e.target.result;
                    } else {
                        image.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    });
});

</script>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.querySelector('#tags');
    const existingTags = @json($brand->tags);

    new Tagify(input, {
        originalInputValueFormat: valuesArr => JSON.stringify(valuesArr.map(item => item.value))
    }).addTags(existingTags);
});
</script>