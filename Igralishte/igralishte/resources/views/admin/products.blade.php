<x-app-layout>
<script src="//unpkg.com/alpinejs" defer></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto px-4 pt-5">
    <div class="grid grid-cols-1 gap-4 mb-8 md:w-1/3">

        @php
$viewType = $viewType ?? 'small';
        @endphp

        
<div class="flex justify-between items-center">
            <!-- Search Bar with Dropdown for Search -->
            <div class="search-wrapper">
            <form action="{{ route('products') }}" method="GET" class="search-form">
                <div class="input-group">
                    <input type="text" name="query" value="{{ $searchQuery ?? '' }}" placeholder="Пребарувај..." id="searchInput" class="search-input search-bar">
                    <button type="submit" class="search-icon"><i class="fas fa-search"></i></button>
                    <div class="dropdown">
                        <button type="button" class="dropdown-toggle"><i class="fas fa-chevron-down"></i></button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" data-value="productName">Име на продукт</a>
                            <a class="dropdown-item" href="#" data-value="brandName">Име на бренд</a>
                            <a class="dropdown-item" href="#" data-value="categoryName">Име на категорија</a>
                        </div>
                    </div>
                    <input type="hidden" name="searchType" id="searchType" value="{{ $searchType ?? 'productName' }}">
                    <input type="hidden" name="viewType" value="{{ $viewType }}">
                </div>
            </form>
        </div>





            <div class="flex justify-end mb-4 ml-1">
            <button id="largeViewBtn" class="view-toggle-btn px-2 py-2 my-0 text-white border border-gray-300 hover:bg-pink-300 rounded mr-1">
                        <svg width="14" height="14" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.70833 4.95833C1.41042 4.95833 1.15529 4.85217 0.942959 4.63983C0.730626 4.4275 0.62464 4.17256 0.625001 3.875V1.70833C0.625001 1.41042 0.731168 1.15529 0.943501 0.942959C1.15583 0.730626 1.41078 0.62464 1.70833 0.625001H3.875C4.17292 0.625001 4.42804 0.731168 4.64038 0.943501C4.85271 1.15583 4.9587 1.41078 4.95833 1.70833V3.875C4.95833 4.17292 4.85217 4.42804 4.63983 4.64038C4.4275 4.85271 4.17256 4.9587 3.875 4.95833H1.70833ZM1.70833 10.375C1.41042 10.375 1.15529 10.2688 0.942959 10.0565C0.730626 9.84417 0.62464 9.58922 0.625001 9.29167V7.125C0.625001 6.82708 0.731168 6.57196 0.943501 6.35963C1.15583 6.14729 1.41078 6.04131 1.70833 6.04167H3.875C4.17292 6.04167 4.42804 6.14783 4.64038 6.36017C4.85271 6.5725 4.9587 6.82745 4.95833 7.125V9.29167C4.95833 9.58958 4.85217 9.84471 4.63983 10.057C4.4275 10.2694 4.17256 10.3754 3.875 10.375H1.70833ZM7.125 4.95833C6.82708 4.95833 6.57196 4.85217 6.35963 4.63983C6.14729 4.4275 6.04131 4.17256 6.04167 3.875V1.70833C6.04167 1.41042 6.14783 1.15529 6.36017 0.942959C6.5725 0.730626 6.82745 0.62464 7.125 0.625001H9.29167C9.58958 0.625001 9.84471 0.731168 10.057 0.943501C10.2694 1.15583 10.3754 1.41078 10.375 1.70833V3.875C10.375 4.17292 10.2688 4.42804 10.0565 4.64038C9.84417 4.85271 9.58922 4.9587 9.29167 4.95833H7.125ZM7.125 10.375C6.82708 10.375 6.57196 10.2688 6.35963 10.0565C6.14729 9.84417 6.04131 9.58922 6.04167 9.29167V7.125C6.04167 6.82708 6.14783 6.57196 6.36017 6.35963C6.5725 6.14729 6.82745 6.04131 7.125 6.04167H9.29167C9.58958 6.04167 9.84471 6.14783 10.057 6.36017C10.2694 6.5725 10.3754 6.82745 10.375 7.125V9.29167C10.375 9.58958 10.2688 9.84471 10.0565 10.057C9.84417 10.2694 9.58922 10.3754 9.29167 10.375H7.125Z" fill="#232221"/>
                        </svg>
                </button>
                <button id="smallViewBtn" class="view-toggle-btn px-2 py-2 my-0 text-white border border-gray-300 hover:bg-pink-300 rounded">
                        <svg width="14" height="14" viewBox="0 0 11 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.23438 7.95312H9.76562M1.23438 4.70312H9.76562M1.23438 1.45312H9.76562" stroke="#232221" stroke-width="1.21875" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                </button>
            </div>
</div>


        <div class="flex justify-end items-center">
            <span class="text-sm text-gray-600 font-semibold mr-3">Додај нов продукт</span>

            <a href="{{ route('create') }}" class="inline-block m-0 border border-transparent font-medium rounded-xl shadow-sm text-white bg-brand-gradient hover:bg-brand-gradient">
                <span class="icon-circle flex justify-items-center items-center">
                    <i class="fas fa-plus"></i>
                </span>
            </a>
        </div>

        @if(session('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    {{ session('success') }}
                </div>
            @endif

        <div id="smallCardsContainer" class="{{ $viewType == 'large' ? 'hidden' : '' }}">


            @forelse  ($products as $product)
            <div class="my-5 px-5 py-5 cursor-pointer product-card small-card border border-gray-200 bg-white shadow-md rounded-lg flex items-center justify-between" data-product-name="{{ strtolower($product->product_name) }}">
                <div class="w-2/3 overflow-hidden">
                    <h5 class="text-md font-semibold truncate"><span class="text-custom-olive">{{ $product->id }} </span>{{ $product->product_name }}</h5>
                </div>
                <div class="flex justify-end space-x-1">
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-edit">
                        <button class="bg-gray-50 border border-gray-300 hover:bg-yellow-400 text-white font-bold rounded-full p-2 w-10 h-10 flex items-center justify-center">
                            <i class="fa-regular fa-pen-to-square text-black text-sm"></i>
                        </button>
                    </a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" id="deleteFormProducts">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDeleteProducts(event, this.closest('form'))" class="btn btn-delete bg-gray-50 border border-gray-300 hover:bg-red-500 text-white font-bold rounded-full p-2 w-10 h-10 flex items-center justify-center">
                            <i class="text-sm fa-solid fa-trash text-black"></i>
                        </button>

                    </form>
                </div>
            </div>
            @empty
            <div class="text-center py-10">
                <p class="text-lg text-gray-700">
                    Нема пронајдено резултати за: <strong>{{ $searchQuery }}</strong>
                </p>
            </div>
        @endforelse
                {{ $products->appends(['viewType' => 'small'])->links('vendor.pagination.custom') }}
    </div>



<!-- Large Cards Container -->
<div id="largeCardsContainer" class="{{ $viewType == 'small' ? 'hidden' : '' }}">


    @forelse ($products as $product)
        <div class="product-card border rounded-lg p-4 cursor-pointer relative overflow-hidden mb-5">
            <!-- Quantity Left -->
            <div class="absolute top-0 left-0 bg-gray-200 px-2 py-1 text-xs font-bold rounded-br-lg">
                @php $quantity = $product->productVariants->sum('quantity'); @endphp
                @if ($quantity === 0)
                    продадено
                @elseif ($quantity === 1)
                    останато: само едно парче
                @else
                    останато: {{ $quantity }} парчиња
                @endif
            </div>





                <!-- Product Image  -->
             @if($product->productImages->isNotEmpty())
                <div class="relative">
                    @foreach($product->productImages as $index => $image)
                        <img src="{{ asset('storage/' . $image->image) }}" 
                             alt="Product Image" 
                             class="carousel-image w-full rounded-md h-52 object-cover mx-auto md:h-full mt-5 {{ $index == 0 ? 'active' : '' }}"
                             data-index="{{ $index }}">
                    @endforeach
                    <button class="carousel-button-prev absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-400 text-white p-1 rounded-full" 
                        onclick="event.stopPropagation(); carouselChange(-1, this.parentNode);">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button class="carousel-button-next absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-400 text-white p-1 rounded-full" 
                        onclick="event.stopPropagation(); carouselChange(1, this.parentNode);">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>

                </div>
            @else
                <img src="{{ asset('path/to/default-image.jpg') }}" alt="Default Image" class="w-full rounded-md h-52 object-cover mx-auto md:h-full mt-5">
            @endif


            <div class="flex justify-between items-center">
            <h2 class="font-normal text-3xl text-center truncate" style="font-family: 'Cormorant Garamond', serif;">{{ $product->product_name }}</h2>
            <p class="text-normal text-xl font-bold text-custom-olive">
            0{{ $product->id }}
            </p>

            </div>

            <div class="flex items-center justify-start mt-2">
                <span class="font-normal mr-1 text-xl text-gray-500" style="font-family: 'Cormorant Garamond', serif;">Боја:</span>
                @foreach ($product->productVariants->pluck('color')->unique() as $color)
                    <span class="h-4 w-4 mr-2 inline-block rounded-sm border border-gray-200" style="background-color: {{ $color->color_code }};"></span>
                @endforeach
            </div>

            <div class="flex justify-between items-center">
            <p class="mt-2 mr-2 text-gray-500 text-xl" style="font-family: 'Cormorant Garamond', serif;">
                Величина:
                <span class="text-black">{{ $product->productVariants->pluck('size.size')->unique()->implode(', ') }}</span>
            </p>
            <p class="font-normal text-xl text-right mt-2 text-gray-500" style="font-family: 'Cormorant Garamond', serif;">
                Цена: 
                @if($product->discounted_price != $product->price)
                    <span class="text-gray-500 line-through">{{ (int) $product->price }} ден.</span>
                    <span class="text-black">{{ (int) $product->discounted_price }} ден.</span>
                @else
                    <span class="text-black">{{ (int) $product->price }} ден.</span>
                @endif
            </p>


            </div>

        </div>
        @empty
            <div class="text-center py-10">
                <p class="text-lg text-gray-700">
                    Нема пронајдено резултати за: <strong>{{ $searchQuery }}</strong>
                </p>
            </div>
        @endforelse
    {{ $products->appends(['viewType' => 'large'])->links('vendor.pagination.custom') }}

    </div>
 </div>
</div>







<script>

document.addEventListener('DOMContentLoaded', function() {
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    const dropdownMenu = document.querySelector('.dropdown-menu');
    const searchInput = document.getElementById('searchInput');
    const initialPlaceholder = searchInput.placeholder;

    dropdownToggle.addEventListener('click', function(event) {
        event.stopPropagation();
        dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    });

    window.addEventListener('click', function(e) {
        if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.style.display = 'none';
        }
    });

    dropdownMenu.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const value = this.getAttribute('data-value');
            document.getElementById('searchType').value = value;
            dropdownMenu.style.display = 'none';

            const searchText = 'Пребарувај по ' + this.textContent;
            searchInput.placeholder = searchText;

            searchInput.focus();
        });
    });
});



document.addEventListener('DOMContentLoaded', () => {
    const smallViewBtn = document.getElementById('smallViewBtn');
    const largeViewBtn = document.getElementById('largeViewBtn');
    const smallCardsContainer = document.getElementById('smallCardsContainer');
    const largeCardsContainer = document.getElementById('largeCardsContainer');

    smallViewBtn.addEventListener('click', () => {
        smallCardsContainer.classList.remove('hidden');
        largeCardsContainer.classList.add('hidden');
        smallViewBtn.classList.add('active');
        largeViewBtn.classList.remove('active');
    });

    largeViewBtn.addEventListener('click', () => {
        smallCardsContainer.classList.add('hidden');
        largeCardsContainer.classList.remove('hidden');
        largeViewBtn.classList.add('active');
        smallViewBtn.classList.remove('active');
    });

    const viewType = '{{ $viewType }}';
    if (viewType === 'large') {
        largeViewBtn.click();
    } else {
        smallViewBtn.click();
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const smallViewBtn = document.getElementById('smallViewBtn');
    const largeViewBtn = document.getElementById('largeViewBtn');

    smallViewBtn.addEventListener('click', () => {
        changeViewType('small');
    });

    largeViewBtn.addEventListener('click', () => {
        changeViewType('large');
    });

    function changeViewType(viewType) {
        let currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('viewType', viewType);
        currentUrl.searchParams.set('page', 1);

        window.location.href = currentUrl.toString();
    }

    
});



</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Your script here
    console.log(document.getElementById('deleteForm-47'));
});
</script>


<script>
function carouselChange(step, carouselContainer) {
    let images = carouselContainer.querySelectorAll('.carousel-image');
    let currentIndex = Array.from(images).findIndex(image => image.classList.contains('active'));
    
    if (currentIndex !== -1) {
        images[currentIndex].classList.remove('active');
    }
    
    let newIndex = currentIndex + step;
    if (newIndex >= images.length) newIndex = 0;
    if (newIndex < 0) newIndex = images.length - 1;

    images[newIndex].classList.add('active');
}
</script>



<script>
function confirmDeleteProducts(event, form) {
    event.preventDefault();
    Swal.fire({
        title: 'Дали сте сигурни дека сакате да го избришете?',
        text: "Оваа акција е неповратна.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Да, избриши го!',
        cancelButtonText: 'Не'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}

</script>

<style>
.carousel-image {
    transition: opacity 0.5s ease-in-out;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    opacity: 0;
}

.carousel-image.active {
    opacity: 1;
    position: relative;
}
</style>


<style>
    .view-toggle-btn.active {
        background-color: lightpink;
    }








.search-wrapper {
    position: relative;
    max-width: 500px;
}

.input-group {
    display: flex;
    align-items: center;
    position: relative;
}


@media (min-width: 768px) {
    .search-input {
    width: 100%;
    /* or */
    min-width: 400px;
}
}

@media (min-width: 375px) {
    .search-input {
    width: 100%;
    /* or */
    min-width: 180px;
}
}

@media (min-width: 425px) {
    .search-input {
    width: 100%;
    /* or */
    min-width: 220px;
}
}

@media (min-width: 1000px) {
    .search-input {
    width: 100%;
    /* or */
    min-width: 400px;
}
}

.search-input {
font-size: 10px;
}
.search-icon,
.dropdown-toggle {
    position: absolute;
    right: 0;
    background: none;
    border: none;
    cursor: pointer;
}

.search-icon {
    right: 25px;
    top: 15px;
}

.dropdown-toggle {
    top:15px;
    right: 5px;
}

.dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid #ccc;
    z-index: 1000;
}

.dropdown-menu .dropdown-item {
    display: block;
    padding: 10px;
    cursor: pointer;
}

.dropdown-menu .dropdown-item:hover {
    background-color: #f0f0f0;
}

</style>
</x-app-layout>
