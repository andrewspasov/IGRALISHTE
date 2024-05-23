@extends('layouts.admin-layout')

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<div class="container mx-auto px-6 py-10">
    <form action="{{ route('store') }}" method="POST" enctype="multipart/form-data"  class="md:w-1/3 md:mx-auto" id="productForm">
        @csrf

        <div class="flex justify-between mb-5">

            <div class="flex items-center justify-between">
                <a href="{{ route('products') }}"><i class="fa-solid fa-arrow-left text-2xl"></i></a><span class="text-xl font-bold ml-4">Продукт</span>
            </div>
            <div class="w-30">
                <select name="is_active" id="is_active" class="appearance-none w-full shadow-sm text-xs border-gray-300 text-black text-left font-semibold rounded-md focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500">
                    <option disabled {{ old('is_active') === null ? 'selected' : '' }}>Статус</option>
                    <option value="1" {{ old('is_active') == "1" ? 'selected' : '' }}>Активен</option>
                    <option value="0" {{ old('is_active') === "0" ? 'selected' : '' }}>Архивиран</option>
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
        <div class="space-y-2 pb-4">
        @error('product_name')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
         @enderror
            <label for="product_name" class="block text-sm font-medium text-gray-700">Име на продукт:</label>
            <input type="text" class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="product_name" name="product_name" value="{{ old('product_name') }}">
        </div>

        <div class="space-y-2 pb-4">
        @error('description')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
         @enderror
            <label for="description" class="block text-sm font-medium text-gray-700">Опис:</label>
            <textarea class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="description" name="description">{{ old('description') }}</textarea>
        </div>

        <div class="space-y-2 pb-4">
        @error('price')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
         @enderror
            <label for="price" class="block text-sm font-medium text-gray-700">Цена (ден.):</label>
            <input type="number" class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="price" name="price" step="0.01" value="{{ old('price') }}">
        </div>


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

            <button type="button" id="add-size-color" class="px-4 py-2 border border-transparent text-sm font-semibold rounded-md text-black bg-green-400">Додај</button>
        </div>



        <div class="space-y-2 pb-4">
        @error('desc_for_size')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
         @enderror
            <label for="desc_for_size" class="block text-sm font-medium text-gray-700">Совет за величина(и):</label>
            <textarea class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="desc_for_size" name="desc_for_size">{{ old('desc_for_size') }}</textarea>
        </div>

        <div class="space-y-2 pb-4">
                @error('how_to_use')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
         @enderror
            <label for="how_to_use" class="block text-sm font-medium text-gray-700">Насоки за одржување:</label>
            <textarea class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="how_to_use" name="how_to_use">{{ old('how_to_use') }}</textarea>
        </div>


        <div class="space-y-2 pb-4">
                 @error('tags.*')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
                @error('tags')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
    <label for="tagsInput" class="block text-sm font-medium text-gray-700">Ознаки:</label>
    <div id="tagsContainer" class="tags-container block shadow-sm sm:text-sm border border-gray-300 rounded-md p-2">
    <input type="text" id="tagsInput" name="tags" class="bg-transparent outline-none w-full" value="{{ old('tags') }}">
    </div>
</div>




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

                <label class="text-black text-sm font-semibold mb-2">Слики:</label>
                <div id="image-upload-wrapper">
                    <!-- Image slot 0 -->
                    <div class="image-upload-box">
                        <input type="file" name="images[]" id="image-0" class="hidden" onchange="addNewImageSlot(event, 0)">
                        <label for="image-0" class="image-upload-label">+</label>
                    </div>
                </div>


        <div class="flex justify-between items-center">
        <div class="pb-5 mb-5 block w-28">
        @error('brand')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
         @enderror
            <label for="brand" class="block text-sm font-medium text-gray-700 pb-5">Бренд:</label>
            <select class="custom-brand-select block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="brand" name="brand_id">
            <option disabled selected>Одбери</option>

                @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->brand_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="pb-5 mb-5 block w-28">
            <label for="brand_category" class="block text-sm font-medium text-gray-700 pb-5">Категорија:</label>
            <select class="custom-brand-category-select block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="brand_category" name="brand_category_id">
            <option disabled selected>Одбери</option>
            
            </select>
        </div>
        </div>

        <div class="flex justify-start items-center">
            <span class="text-sm text-gray-600 font-semibold mr-3">Додај попуст</span>

            <a class="inline-block m-0 border border-transparent font-medium rounded-xl shadow-sm text-white bg-brand-gradient hover:bg-brand-gradient">
                <span class="icon-circle flex justify-items-center items-center cursor-pointer">
                    <i class="fas fa-plus"></i>
                </span>
            </a>
                <div id="selectedDiscountInfo"></div>

        </div>


                        <!-- Discount Modal -->
                <div id="discountModal" class="mt-0 hidden fixed inset-0 bg-black bg-opacity-0 z-50 my-auto">
                <div class="flex items-center min-h-screen justify-center">
                    <div class="bg-white p-10 rounded-lg shadow-lg max-w-md mx-auto">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-medium text-gray-800">Избери Попуст</h2>
                        <button class="text-gray-600 focus:outline-none" onclick="closeModal()" type="button">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="mt-4">
                        <select id="discountSelect" class="block w-full p-2 border border-gray-300 rounded-md">
                        </select>
                        <button id="applyDiscount" class="px-4 py-2 border border-transparent text-sm font-semibold rounded-md text-black bg-green-600 mt-5">Избери</button>

                    </div>
                    <div id="discountPreview" class="mt-4 text-gray-600"></div>
                    </div>
                    
                </div>
                </div>


                <div id="discountAppliedMessage" class="text-green-600"></div>

                <input type="hidden" name="selectedDiscountId" id="selectedDiscountId" value="">

            <input type="hidden" name="combinations" id="combinationsInput">

            <div class="flex justify-beteen items-center pt-5 mt-5">
            <button type="submit" class="w-full md:w-1/4 text-xl text-white bg-black hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-bold rounded-xl text-md px-5 py-3.5 me-2 mb-2 dark:bg-black dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Зачувај</button>

            <a href="{{ route('products') }}" class="text-normal underline text-black mx-5">Откажи</a>
            </div>
    </form>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#brand, #brand_category').select2();

    let oldBrandCategoryId = "{{ old('brand_category_id') }}";
    console.log(oldBrandCategoryId);

    $('#brand-categories + .select2-selection--single, .select2-selection--multiple').css({
        'padding': '20px 20px',
    });

    $('#productForm').on('submit', function(event) {
        event.preventDefault();
        event.stopPropagation();
    });

    $('#brand').on('change', function() {
        const brandId = $(this).val();
        axios.get(`/api/brand-categories/${brandId}`)
            .then(function(response) {
                let options = '<option value="">Одбери</option>';
                response.data.categories.forEach(function(category) {
                    let isSelected = oldBrandCategoryId == category.id ? 'selected' : '';
                    options += `<option value="${category.id}" ${isSelected}>${category.category_name}</option>`;
                });
                $('#brand_category').html(options).select2();
            })
            .catch(function(error) {
                console.error("Error fetching brand categories:", error);
            });
    });

    if ($('#brand').val() || oldBrandCategoryId) {
        $('#brand').trigger('change');
    }
});
</script>


<script>
$(document).ready(function() {

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
        $(this).removeClass('bg-lightpink text-black').addClass('bg-custom-olive text-white');
        selectedSize = $(this).data('size-id');
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

        if (!sizeId || !colorId || !quantity) {
            alert("Ве молиме, одберете една величина, боја и количина.");
            return;
        }

        const duplicate = combinations.some(combo => combo.sizeId === sizeId && combo.colorId === colorId);
        if (duplicate) {
            alert("Оваа комбинација за величина и боја за продуктот ви е веќе внесена. Ако имате грешка, само направите refresh na browser.");
            return;
        }

        combinations.push({ sizeId, colorId, quantity });
        $('#added-combinations').append(`<div class="font-semibold text-black">Величина: ${selectedSizeName}, Боја: ${selectedColorName}, Количина: ${quantity} (селектирано).</div>`);

        $('.color-box').removeClass('ring-2 ring-offset-2 ring-custom-olive');
        $('.size-box').removeClass('active bg-custom-olive text-white').addClass('bg-lightpink text-black');;
        $('#quantity').val(1);
    });


    $('form').submit(function(e) {
    e.preventDefault();
    
    $('#combinationsInput').val(JSON.stringify(combinations));
    
    this.submit();
});


});
</script>



<script>
document.addEventListener('DOMContentLoaded', function() {
    var input = document.querySelector('#tagsInput');
    if (input) {
        var existingTags = input.value ? JSON.parse(input.value) : [];
        
        existingTags = existingTags.map(tag => typeof tag === 'string' ? {value: tag} : tag);

        var tagify = new Tagify(input, {
        });

        tagify.addTags(existingTags);

        document.querySelector('form').addEventListener('submit', function(e) {
            var tagifyValues = tagify.value.map(tag => tag.value);
            var tagifyValuesString = JSON.stringify(tagifyValues);
            input.value = tagifyValuesString;
        });
    }
});

</script>


<script>

$(document).ready(function() {

    $('.icon-circle').click(function(e) {
        e.preventDefault();
        $('#discountModal').removeClass('hidden');
        fetchDiscounts();
    });

    window.closeModal = function() {
        $('#discountModal').addClass('hidden');
    }

    function fetchDiscounts() {
        $.ajax({
            url: '/api/discounts',
            method: 'GET',
            success: function(response) {
                let options = '<option selected disabled value="">Попусти</option>';
                response.forEach(discount => {
                    options += `<option value="${discount.id}" data-percent="${discount.amount}">${discount.discount_name}</option>`;
                });
                $('#discountSelect').html(options);
            },
            error: function(error) {
                console.error("Error fetching discounts:", error);
            }
        });
    }

    $('#discountSelect').change(function() {
        const percent = $('option:selected', this).data('percent');
        $('#discountPreview').text(`Вредност на овој попуст: ${percent}%`);
    });



$('#applyDiscount').click(function(e) {
    e.preventDefault();
    e.stopPropagation();

    var selectedDiscountId = $('#discountSelect').val();

    if (!selectedDiscountId) {
        $('#discountAppliedMessage').html('Почитувани, немате изберено попуст за овој продукт!').css('color', 'red');
        $('#selectedDiscountId').val('');
        $('#discountModal').addClass('hidden');

    } else {
        console.log("Selected Discount ID:", selectedDiscountId);

        $('#selectedDiscountId').val(selectedDiscountId);

        $('#discountAppliedMessage').html('Попустот е назначен на овој продукт успешно.').css('color', 'green');

        $('#discountModal').addClass('hidden');
    }

    $('#discountSelect').val('');
    $('#discountPreview').empty();
});
});

</script>





<script>
function addNewImageSlot(event, index) {
    if (event.target.files.length > 0) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const label = document.querySelector(`label[for='image-${index}']`);
            label.style.backgroundImage = `url('${e.target.result}')`;
            label.textContent = '';
        };
        reader.readAsDataURL(event.target.files[0]);

        const totalImages = document.querySelectorAll('.image-upload-box').length;
        const filledSlots = document.querySelectorAll('input[type="file"]:not([id="image-' + (totalImages - 1) + '"])').length;

        if (index === totalImages - 1 && totalImages <= 10 && filledSlots < 9) {
            const newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.name = 'images[]';
            newInput.id = `image-${totalImages}`;
            newInput.className = 'hidden';
            newInput.onchange = function(e) { addNewImageSlot(e, totalImages); };
            
            const newLabel = document.createElement('label');
            newLabel.htmlFor = `image-${totalImages}`;
            newLabel.className = 'image-upload-label';
            newLabel.textContent = '+';

            const newImageBox = document.createElement('div');
            newImageBox.className = 'image-upload-box';
            newImageBox.appendChild(newInput);
            newImageBox.appendChild(newLabel);

            document.getElementById('image-upload-wrapper').appendChild(newImageBox);
        }
    }
}
</script>


<style>
    .bg-lightpink {
        background-color: #FFDBDB;
    }

#discountModal .modal-content {
    background: #fff;
    padding: 20px;
    border-radius: 5px;
}

</style>





<style>
    .image-upload-box label {
        width: 100px;
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed #cbd5e1;
        border-radius: 0.375rem;
        margin-right: 1rem;
        background-position: center;
        background-repeat: no-repeat;
    }


    #image-upload-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
}

.image-upload-label {
    display: inline-block;
    width: 100px;
    height: 100px;
    border: 1px dashed #ccc;
    background-size: contain;
    background-position: center;
    background-repeat: no-repeat;
    cursor: pointer;
    text-align: center;
    line-height: 100px;
    font-size: 24px;
}

.hidden {
    display: none;
}

.image-upload-box {
    position: relative;
}

</style>
