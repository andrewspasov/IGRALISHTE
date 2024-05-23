@extends('layouts.admin-layout')

<div class="container mx-auto p-6 md:mt-5 md:pt-5">
    <form action="{{ route('admin.discount.store') }}" method="POST" enctype="multipart/form-data" class="md:w-1/3 md:mx-auto">
        @csrf
        <div class="flex justify-between pb-5 mb-5">

        <div class="flex items-center justify-between">
            <a href="{{ route('discounts') }}"><i class="fa-solid fa-arrow-left text-2xl"></i></a><span class="text-xs font-bold ml-4">Попуст/Промо код</span>
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
                    <p class="text-red-500 text-xs italic mb-5 pb-5">{{ $message }}</p>
        @enderror
        @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
            {{ session('success') }}
        </div>
        @endif


        <div class="mb-4">
            @error('discount_name')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
            <label for="discount_name" class="block text-black text-sm font-semibold mb-2">Име на попуст / промо код:</label>
            <input type="text" name="discount_name" id="discount_name" value="{{ old('discount_name') }}" class="w-full shadow-sm sm:text-sm border-gray-300 text-gray-500 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500">
        </div>

        <div class="mb-4">
            @error('amount')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
            <label for="amount" class="block text-black text-sm font-semibold mb-2">Попуст (%):</label>
            <input type="number" name="amount" id="amount" value="{{ old('amount') }}" class="w-full shadow-sm sm:text-sm border-gray-300 text-gray-500 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500" min="1" max="100">
        </div>

        <div class="mb-4">
            @error('discount_category_id')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
            <label for="discount_category_id" class="block text-black text-sm font-semibold mb-2">Категорија:</label>
            <select name="discount_category_id" id="discount_category_id" class="w-full text-gray-400 bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 pr-8 rounded focus:outline-none">
                <option disabled selected>Одбери</option>
                @foreach ($discountCategories as $category)
                    <option value="{{ $category->id }}" {{ old('discount_category_id') == $category->id ? 'selected' : '' }}>{{ $category->discount_category_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            @error('product_ids')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
            <label for="product_ids" class="block text-black text-sm font-semibold mb-2">Постави попуст на:</label>
            <input type="text" name="product_ids" id="product_ids" value="{{ old('product_ids') }}" class="w-full shadow-sm sm:text-sm border-gray-300 text-gray-500 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500">
        </div>


        <div class="flex justify-beteen items-center pt-5 mt-5">
            <button type="submit" class="w-full md:w-1/4 text-xl text-white bg-black hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-bold rounded-xl text-md px-5 py-3.5 me-2 mb-2 dark:bg-black dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Зачувај</button>

            <a href="{{ route('discounts') }}" class="text-sm underline text-black mx-2">Откажи</a>
        </div>
    </form>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('product_ids');

    input.addEventListener('input', function() {
        const parts = input.value.split(',');

        const transformedParts = parts.map(part => {
            const trimmedPart = part.trim();
            if (trimmedPart && !isNaN(trimmedPart)) {
                return ' #' + trimmedPart;
            }
            return part;
        });

        input.value = transformedParts.join(',');
    });
});
</script>










