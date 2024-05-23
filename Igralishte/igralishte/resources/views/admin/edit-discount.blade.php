@extends('layouts.admin-layout')

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<div class="container mx-auto p-6">
    <form action="{{ route('discount.update', $discount->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-4 mb-8 md:w-1/3 md:mx-auto">
        @csrf
        @method('PUT')


        <div class="flex justify-between mb-5">

<div class="flex items-center justify-between">
    <a href="{{ route('discounts') }}"><i class="fa-solid fa-arrow-left text-2xl"></i></a><span class="text-xs md:text-2xl font-bold ml-4">Едитирај Попуст/Промо код</span>
</div>
<div class="w-30">
    <select name="is_active" id="is_active" class="appearance-none w-full shadow-sm text-xs border-gray-300 text-black text-left font-semibold rounded-md focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 truncate">
                <option value="1" {{ $discount->is_active ? 'selected' : '' }}>Активен</option>
                <option value="0" {{ !$discount->is_active ? 'selected' : '' }}>Арихивиран</option>
            </select>
</div>
</div>
        <div class="mb-4">
            @error('is_active')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror

        </div>


        <div class="mb-4">
            @error('discount_name')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
            <label for="discount_name" class="block text-black font-semibold text-sm mb-2">Име на попуст:</label>
            <input type="text" name="discount_name" id="discount_name" value="{{ $discount->discount_name }}" class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-500 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            @error('amount')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
            <label for="amount" class="block text-black font-semibold text-sm mb-2">Износ на попуст (%):</label>
            <input type="number" name="amount" id="amount" value="{{ $discount->amount }}" class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-500 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            @error('discount_category_id')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
            <label for="discount_category_id" class="block text-black font-semibold text-sm mb-2">Категорија на попуст:</label>
            <select id="discount_category_id" name="discount_category_id" class="w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:bg-white focus:border-gray-500">
                @foreach($discountCategories as $category)
                    <option value="{{ $category->id }}" {{ $discount->discount_category_name == $category->id ? 'selected' : '' }}>{{ $category->discount_category_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            @error('product_ids')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
            @error('product_ids.regex')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
            <label for="product_ids" class="block text-black font-semibold text-sm mb-2">Постави попуст на:</label>
            <input type="text" id="product_ids" name="product_ids" value="{{ old('product_ids', $discount->products->pluck('id')->implode(', ')) }}" class="w-full shadow-sm sm:text-sm border-gray-300 text-gray-500 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500">
        </div>


        <div class="flex justify-beteen items-center pt-5 mt-5">
            <button type="submit" class="w-full md:w-1/4 text-xl text-white bg-black hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-bold rounded-xl text-md px-5 py-3.5 me-2 mb-2 dark:bg-black dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Зачувај</button>

            <a href="{{ route('discounts') }}" class="text-sm underline text-black mx-2">Откажи</a>
        </div>
        </form>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
            {{ session('success') }}
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('product_ids');

    
    const formatInputValue = () => {
        if (input.value.slice(-1) === ',') return;

        let ids = input.value.split(',').map(id => {
            id = id.trim();
            return id && !isNaN(id) && !id.startsWith('#') ? '#' + id : id;
        }).join(', ');
        input.value = ids;
    };

    let lastValue = '';

    input.addEventListener('input', function(event) {
        if (lastValue.length > input.value.length && lastValue[input.value.length] === ',') {
            input.value = lastValue.slice(0, input.value.length) + lastValue.slice(input.value.length + 1);
        }

        formatInputValue();
        lastValue = input.value;
    });

    input.addEventListener('keydown', function(event) {
        if (event.key === 'Backspace') {
            lastValue = input.value;
        }
    });

    formatInputValue();
});


</script>
