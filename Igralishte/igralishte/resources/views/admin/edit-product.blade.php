@extends('layouts.admin-layout')

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<div class="container mx-auto px-6 py-10">
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="md:w-1/3 md:mx-auto" id="productForm">
        @csrf
        @method('PUT')

        <div class="flex justify-between mb-5">

<div class="flex items-center justify-between">
    <a href="{{ route('products') }}"><i class="fa-solid fa-arrow-left text-2xl"></i></a><span class="text-xs md:text-2xl font-bold ml-4">Едитирај Продукт</span>
</div>
<div class="w-30">
    <select name="is_active" id="is_active" class="appearance-none w-full shadow-sm text-xs border-gray-300 text-black text-left font-semibold rounded-md focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 truncate">
                <option value="1" {{ $product->is_active ? 'selected' : '' }}>Активен</option>
                <option value="0" {{ !$product->is_active ? 'selected' : '' }}>Арихивиран</option>
            </select>
</div>
</div>

        @if(session('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @error('is_active')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
         @enderror

        <div class="mb-4">
        @error('product_name')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
         @enderror

            <label for="product_name" class="block mb-2 text-sm font-medium text-gray-700">Име на продукт:</label>
            <input type="text" name="product_name" id="product_name" value="{{ old('product_name', $product->product_name) }}" class="block w-full p-2.5 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        <div class="mb-4">
        @error('description')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
         @enderror
            <label for="description" class="block mb-2 text-sm font-medium text-gray-700">Опис:</label>
            <textarea name="description" id="description" rows="4" class="block w-full p-2.5 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="mb-4">
        @error('price')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
         @enderror
            <label for="price" class="block mb-2 text-sm font-medium text-gray-700">Цена (ден.):</label>
            <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" class="block w-full p-2.5 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500" step="0.01" required>
        </div>

        @if($product->productVariants->isNotEmpty())
            <div class="mb-4 overflow-x-auto">
                <h4 class="text-lg md:text-xl font-semibold mb-2">Достапност на продукт:</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-2 py-1 text-left text-xs md:px-4 md:py-2 md:text-sm">Величина</th>
                                <th class="px-2 py-1 text-left text-xs md:px-4 md:py-2 md:text-sm">Боја</th>
                                <th class="px-2 py-1 text-left text-xs md:px-4 md:py-2 md:text-sm">Количина</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->productVariants as $variant)
                                <tr class="border-b">
                                    <td class="px-2 py-1 text-xs md:px-4 md:py-2 md:text-sm">{{ $variant->size->size }}</td>
                                    <td class="px-2 py-1 text-xs md:px-4 md:py-2 md:text-sm">{{ $variant->color->color_name }}</td>
                                    <td class="px-2 py-1 text-xs md:px-4 md:py-2 md:text-sm">{{ $variant->quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

@else
    <p class="text-red-500">Овој продукт нема ниту едно парче моментално</p>
@endif


<button id="resetColorSizes" class="px-4 my-4 py-2 border border-transparent text-sm font-semibold rounded-md text-white bg-red-400 hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" data-url="{{ route('products.resetVariants', ['product' => $product->id]) }}" data-product-id="{{ $product->id }}">
    Избриши ги веќе достапните парчиња за овој продукт кои се внесени во датабаза
</button>




        <div id="dynamic-options" class="space-y-4">

        <div class="dynamic-form bg-gray-100 p-5">

        <div class="flex items-center space-x-2 pb-4">
        <label for="quantity" class="font-semibold ">Количина:</label>

            <button type="button" id="decrement-quantity" class="text-gray-600 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 h-8 w-8 rounded-full border border-gray-300">
                <span class="m-auto">-</span>
            </button>
            <input type="number" id="quantity" name="quantity" min="1" value="1" class="w-10 text-md md:text-2xl font-semibold text-center form-input border-none rounded-md border-gray-300 md:w-16">
            <button type="button" id="increment-quantity" class="text-gray-600 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 h-8 w-8 rounded-full border border-gray-300">
                <span class="m-auto">+</span>
            </button>
        </div>
        <div class="space-y-2 pb-4">
            <div class="flex flex-wrap gap-1 items-center">
            <label class="block text-md font-medium text-gray-700">Величина:</label>
                @foreach($sizes as $size)
                
                    <div class="size-box flex justify-center items-center m-1 cursor-pointer bg-lightpink text-sm text-black font-semibold rounded-md shadow-sm" data-size-id="{{ $size->id }}" data-size-name="{{ $size->size }}" style="width: 28px; height: 28px;">
                        {{ $size->size }}
                    </div>
                @endforeach
            </div>
        </div>


        <div class="space-y-2 pb-4">
                <label for="color" class="block text-md font-medium text-gray-700">Боја:</label>
                <div id="color-selection" class="flex flex-wrap">
                    @foreach($colors as $color)
                        <div class="p-2">
                            <button type="button" class="color-box" data-color-id="{{ $color->id }}" data-color-name="{{ $color->color_name }}" style="background-color: {{ $color->color_code }}; width: 28px; height: 28px; border: 1px solid lightgray; border-radius: 20%;" title="{{ $color->color_name }}"></button>
                        </div>
                    @endforeach
                    
                </div>
            </div>

            <input type="hidden" id="selectedSize" name="selectedSize">
            <input type="hidden" id="selectedColor" name="selectedColor">

            <div id="added-combinations" class="space-y-2"></div>
            <input type="hidden" name="combinations" id="combinationsInput" value="">

            <button type="button" id="add-size-color" class="px-4 py-2 border border-transparent text-sm font-semibold rounded-md text-black bg-green-400">Додај</button>
        </div>



        <div class="mb-4 image-upload-container-edit">
            @error('images.*.image')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
        @error('images.*.max')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
        @error('images.*')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
        @error('images')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    <label for="images" class="block text-gray-700 text-sm font-bold mb-2">Слики:</label>
    <div class="flex justify-center flex-wrap">
        @foreach($product->productImages as $image)
            <div class="image-input-wrapper-edit relative mr-2 mb-2" style="width: 100px; height: 100px;">
                <!-- Notice the change in the path inside the asset function -->
                <img src="{{ asset('storage/' . $image->image) }}" alt="Product Image" style="width: 100%; height: 100%; object-fit: cover;">
                <input type="file" name="new_images[]" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
            </div>
        @endforeach
        @for ($i = count($product->productImages); $i < 10; $i++)
            <div class="image-input-wrapper-edit relative mr-2 mb-2" style="width: 100px; height: 100px;">
                <div class="absolute inset-0 flex items-center justify-center bg-gray-200 rounded-md cursor-pointer">
                    <span class="text-xl font-bold text-gray-700">+</span>
                </div>
                <input type="file" name="new_images[]" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
            </div>
        @endfor
    </div>
</div>



        <div class="flex justify-between items-center">
        <div class="pb-5 mb-5">
        @error('brand_id')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
         @enderror
            <label for="brand" class="block text-sm font-medium text-gray-700 pb-5">Бренд:</label>
            <select class="sm:text-sm w-28 border-gray-300" id="brand" name="brand_id">

                @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ (old('brand_id', $product->brand_id) == $brand->id) ? 'selected' : '' }}>{{ $brand->brand_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="pb-5 mb-5">

    @error('brand_category_id')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
            <label for="brand_category" class="block text-sm font-medium text-gray-700 pb-5">Категорија:</label>
            <select class="custom-brand-category-select sm:text-sm border-gray-300" id="brand_category" name="brand_category_id">
            <option disabled selected>Одбери</option>

                <!-- Brand categories options will be populated based on brand selection -->
            </select>
            
        </div>
        
        </div>







        <div class="space-y-2 pb-4">
        @error('desc_for_size')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
            <label for="desc_for_size" class="block text-sm font-medium text-gray-700">Совет за величина(и):</label>
            <textarea class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="desc_for_size" name="desc_for_size">{{ $product->desc_for_size }}</textarea>
        </div>

        <div class="space-y-2 pb-4">
        @error('how_to_use')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
            <label for="how_to_use" class="block text-sm font-medium text-gray-700">Насоки за одржување:</label>
            <textarea class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="how_to_use" name="how_to_use">{{ $product->how_to_use }}</textarea>
        </div>


        <div class="space-y-2 pb-4">
        @error('tags')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    <label for="tags" class="block text-sm font-medium text-gray-700">Ознаки:</label>
    <input type="text" id="tags" name="tags" class="w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
    <input type="hidden" name="tagsToAdd" id="tagsToAdd">
    <input type="hidden" name="tagsToRemove" id="tagsToRemove">

</div>



        <span class="text-sm text-gray-600 font-semibold">Додај попуст</span>
<button id="discountModalOpen" class="px-4 py-2 text-sm font-medium focus:outline-none" type="button">
<div class="flex justify-start items-center">
            <a class="inline-block m-0 border border-transparent font-medium rounded-xl shadow-sm text-white bg-brand-gradient hover:bg-brand-gradient">
                <span class="icon-circle flex justify-items-center items-center cursor-pointer">
                    <i class="fas fa-plus"></i>
                </span>
            </a>

        </div>
</button>

        <!-- Discount Modal -->
        <div id="discountModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay, show modal with .block, hide with .hidden -->
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <!-- Modal Header -->
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                    Смени или одбери нов попуст
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 pb-4 sm:p-6 sm:pb-4">
                        <div class="space-y-2 pb-4">
                            @error('discount_id')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                                <label for="discount_id" class="block text-sm font-medium text-gray-700">Попусти:</label>
                                <select name="discount_id" id="discount_id" class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <option value="">Оваа опција е за продуктот да нема никаков попуст</option>
                                    @foreach($discounts as $discount)
                                        <option value="{{ $discount->id }}" {{ ($currentDiscount && $currentDiscount->id == $discount->id) ? 'selected' : '' }}>{{ $discount->discount_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" class="w-full px-4 py-2 mt-3 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" id="discountModalClose">
                            Додај
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <div class="flex justify-beteen items-center pt-5 mt-5">
            <button type="submit" class="w-full md:w-1/4 text-xl text-white bg-black hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-bold rounded-xl text-md px-5 py-3.5 me-2 mb-2 dark:bg-black dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Зачувај</button>

            <a href="{{ route('products') }}" class="text-normal underline text-black mx-5">Откажи</a>
            </div>
    </form>
</div>


<script>
$(document).ready(function() {

    $('#resetSizesColors').on('click', function() {
        if(confirm("Дали сте сигурни дека сакате да ги ресетирате веќе внесените парчиња во датабаза?")) {
            $.ajax({
                url: '/admin/products/reset-variants',
                method: 'POST',
                data: {
                    product_id: "{{ $product->id }}",
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert("Сите комбинации од боја, количина и величина се избришани во датабазе. Можете да внесете нови.");
                    $('.size-box').removeClass('selected-css-class');
                    
                    $('.color-box').removeClass('selected-css-class');

                    $('#quantity').val(1);

                    $('#added-combinations').empty();

                },
                error: function(xhr, status, error) {
                    alert("An error occurred. Please try again.");
                }
            });
        }
    });


});





document.addEventListener('DOMContentLoaded', (event) => {
    document.querySelectorAll('.image-input-wrapper-edit input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let img = input.closest('.image-input-wrapper-edit').querySelector('img');
                    if (img) {
                        console.log('Updating existing img element');
                        img.src = e.target.result;
                    } else {
                        console.log('Creating new img element');
                        img = document.createElement('img');
                        img.src = e.target.result;
                        img.setAttribute('class', 'absolute inset-0 object-cover w-full h-full rounded-md');
                        input.closest('.image-input-wrapper-edit').appendChild(img);
                    }
                };
                reader.onerror = function (error) {
                    console.error('Error occurred:', error);
                };
                reader.readAsDataURL(file);
            } else {
                console.log('No file selected');
            }
        });
    });
});
</script>







<script>

document.addEventListener('DOMContentLoaded', function() {
    $('#brand_category').select2();

    let currentBrandCategoryId = "{{ $product->brandCategory ? $product->brandCategory->id : old('brand_category_id', '') }}";
    console.log(currentBrandCategoryId);

    $('#brand').on('change', function() {
        const brandId = this.value;
        
        axios.get(`/api/brand-categories/${brandId}`)
            .then(function(response) {
                let options = '<option value="">Одбери</option>';
                response.data.categories.forEach(function(category) {
                    let isSelected = currentBrandCategoryId == category.id ? 'selected' : '';
                    options += `<option value="${category.id}" ${isSelected}>${category.category_name}</option>`;
                });

                $('#brand_category').html(options).select2();

                if (currentBrandCategoryId) {
                    $('#brand_category').val(currentBrandCategoryId).trigger('change');
                }
            })
            .catch(function(error) {
                console.error("Error fetching brand categories:", error);
            });
    });

    if ($('#brand').val()) {
        $('#brand').trigger('change');
    }
});

</script>




<script>
$(document).ready(function() {

$('#productForm').submit(function(e) {
        e.preventDefault();

        const combinationsInput = document.getElementById('combinationsInput');
        combinationsInput.value = JSON.stringify(combinations);

        this.submit();
    });

    let selectedSize = null;
    let selectedColor = null;
    let selectedSizeName = '';
    let selectedColorName = '';

    $('.color-box').click(function() {
        selectedColor = $(this).data('color-id');
        selectedColorName = $(this).data('color-name');
        $('.color-box').removeClass('ring-2 ring-offset-2 ring-custom-olive');
        $(this).addClass('ring-2 ring-offset-2 ring-custom-olive');
        $('#selectedColor').val(selectedColor);
    });

    $('.size-box').click(function() {
        selectedSize = $(this).data('size-id');
        selectedSizeName = $(this).data('size-name');
        $('.size-box').removeClass('bg-custom-olive text-white').addClass('bg-lightpink text-black');
        $(this).addClass('bg-custom-olive text-white');
        $('#selectedSize').val(selectedSize);
    });

    $('#increment-quantity').click(function() {
        let value = parseInt($('#quantity').val(), 10);
        $('#quantity').val(value + 1);
    });

    $('#decrement-quantity').click(function() {
        let value = parseInt($('#quantity').val(), 10);
        if (value > 1) {
            $('#quantity').val(value - 1);
        }
    });

    const combinations = [];
    $('#add-size-color').click(function() {
        const sizeId = $('#selectedSize').val();
        const colorId = $('#selectedColor').val();
        const quantity = $('#quantity').val();
        const productId = "{{ $product->id }}";


        if (!sizeId || !colorId || !quantity) {
            alert("Ве молиме, одберете една величина, боја и количина.");
            return;
        }

                const duplicate = combinations.some(combo => combo.sizeId === sizeId && combo.colorId === colorId);
        if (duplicate) {
            alert("Оваа комбинација за величина и боја за продуктот ви е веќе внесена. Ако имате грешка, само направите refresh na browser.");
            return;
        }

        $.ajax({
            url: '/admin/products/check-variant',
            type: 'POST',
            data: {
                size_id: sizeId,
                color_id: colorId,
                product_id: productId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.exists) {
                    alert("Оваа комбинација на боја/величина за овој продукт е веќе внесена.");
                } else {
                    combinations.push({ sizeId, colorId, quantity });
                    $('#added-combinations').append(`<div>Величина: ${selectedSizeName}, Боја: ${selectedColorName}, Колирина: ${quantity} (селектирано).</div>`);
                    resetSelections();
                }
            },
            error: function(xhr, status, error) {
                console.error("An error occurred:", error);
            }
        });
    });

    function resetSelections() {
        $('.color-box').removeClass('ring-2 ring-offset-2 ring-custom-olive');
        $('.size-box').removeClass('bg-custom-olive text-white').addClass('bg-lightpink text-black');
        $('#quantity').val(1);
        $('#selectedSize').val('');
        $('#selectedColor').val('');
    }
});
</script>


<script>
document.addEventListener("DOMContentLoaded", () => {
    const tagsInput = document.querySelector('#tags');
    let tagsToAdd = [];
    let tagsToRemove = [];

    var tagify = new Tagify(tagsInput, {
        originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(',')
    });

    var existingTags = @json($product->tags) || [];
    console.log(existingTags);
    tagify.addTags(existingTags);

    tagify.on('add', (e) => {
        const tagValue = e.detail.data.value;
        if (!existingTags.includes(tagValue)) {
            tagsToAdd.push(tagValue);
        } else {
            tagsToRemove = tagsToRemove.filter(tag => tag !== tagValue);
        }
        updateHiddenInputs();
    });

    tagify.on('remove', (e) => {
        const tagValue = e.detail.data.value;
        if (existingTags.includes(tagValue)) {
            tagsToRemove.push(tagValue);
        } else {
            tagsToAdd = tagsToAdd.filter(tag => tag !== tagValue);
        }
        updateHiddenInputs();
    });

    function updateHiddenInputs() {
        document.getElementById('tagsToAdd').value = tagsToAdd.join(',');
        document.getElementById('tagsToRemove').value = tagsToRemove.join(',');
    }
});
</script>





<script>
$(document).ready(function() {
    $('#resetColorSizes').click(function(e) {
        e.preventDefault();

        const url = $(this).data('url');
        const productId = $(this).data('product-id');

        if (confirm('Дали сте сигурни дека сакате да ги ресетирате веќе внесените парчиња во датабаза?')) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: 'POST',
                data: {
                    product_id: productId
                },
                success: function(response) {
                    if(response.success) {
                        alert("Сите комбинации од боја, количина и величина се избришани во датабазе. Можете да внесете нови.");
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error resetting variants:', error);
                    alert('An error occurred. Please try again.');
                }
            });
        }
    });
});


document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('discountModal');
    const openBtn = document.getElementById('discountModalOpen');
    const closeBtn = document.getElementById('discountModalClose');

    openBtn.addEventListener('click', function () {
        modal.classList.remove('hidden');
        modal.classList.add('block');
    });

    closeBtn.addEventListener('click', function () {
        modal.classList.add('hidden');
        modal.classList.remove('block');
    });
});

</script>


<style>
        .bg-lightpink {
        background-color: #FFDBDB;
    }

    #brand {
        padding: 1px;
        padding-top:1px;
        padding-bottom: 1px;
        padding-left: 10px;
        border-radius: 4px;
        border: 1px solid lightgray;
    }


</style>