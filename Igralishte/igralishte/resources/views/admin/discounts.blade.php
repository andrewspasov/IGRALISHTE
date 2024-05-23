<x-app-layout>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<div class="container mx-auto px-4 pt-5">

    <div class="discount-cards-container grid grid-cols-1 gap-4 mb-8 md:w-1/3">

        <div class="relative">
            <input type="text" id="searchInput-discounts" class="search-bar focus:ring-0 focus-outline-none" placeholder="Пребарувај...">
            <div class="absolute top-0 right-0 ml-3 mt-3 mr-3 mb-3">
                <i class="fa fa-search text-gray-400"></i>
            </div>
        </div>

        <div class="flex justify-end items-center">
            <span class="text-sm text-gray-600 font-semibold mr-3">Додај нов попуст/промо код</span>
            <a href="{{ route('admin.discount.create') }}" class="inline-block m-0 border border-transparent font-medium rounded-xl shadow-sm text-white bg-brand-gradient hover:bg-brand-gradient">
                <span class="icon-circle flex justify-items-center items-center">
                    <i class="fas fa-plus"></i>
                </span>
            </a>
        </div>

        <div id="noResults-discounts" class="text-center" style="display: none;">
            <p class="text-black font-semibold">Попуст со тоа име не постои во системот.</p>
        </div>
        @if(session('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    {{ session('success') }}
                </div>
            @endif

        <h2 class="text-xl font-bold mb-4 title">Активни</h2>

        @foreach ($activeDiscounts as $discount)
        <div class="cursor-pointer card border border-gray-100 bg-white rounded-lg p-1 py-2 w-full">
            <div class="flex items-center justify-around rounded-md px-2 py-2">
                <div class="w-2/3 md:w-full truncate">
                    <h5 class="text-md font-semibold truncate">{{ $discount->discount_name }}</h5>
                </div>
                <div class="flex justify-end space-x-1">
                    <a href="{{ route('discounts.edit', $discount->id) }}" class="btn btn-edit">
                        <button class="bg-gray-50 border border-gray-300 hover:bg-yellow-400 text-white font-bold rounded-full p-2 w-10 h-10 flex items-center justify-center">
                            <i class="fa-regular fa-pen-to-square text-black text-sm"></i>
                        </button>
                    </a>
                    <form action="{{ route('discounts.destroy', $discount->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDeleteDiscounts(event, this.closest('form'))" class="btn btn-delete bg-gray-50 border border-gray-300 hover:bg-red-500 text-white font-bold rounded-full p-2 w-10 h-10 flex items-center justify-center">
                            <i class="text-sm fa-solid fa-trash text-black"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach

    </div>

    <h2 class="text-xl font-bold mb-4 title text-gray-600 mt-5 pt-5">Архива</h2>
    <div class="discount-cards-container grid grid-cols-1 gap-4 mb-8 md:w-1/3">
        @foreach ($inactiveDiscounts as $discount)
        <div class="cursor-pointer card border border-gray-100 bg-white rounded-lg p-1 py-2 w-full">
            <div class="flex items-center justify-around rounded-md px-2 py-2">
                <div class="w-2/3 md:w-full truncate">
                    <h5 class="text-md font-semibold truncate text-gray-400">{{ $discount->discount_name }}</h5>
                </div>
                <div class="flex justify-end space-x-1">
                    <a href="{{ route('discounts.edit', $discount->id) }}" class="btn btn-edit">
                        <button class="bg-gray-50 border border-gray-300 hover:bg-yellow-400 text-white font-bold rounded-full p-2 w-10 h-10 flex items-center justify-center">
                            <i class="fa-regular fa-pen-to-square text-black text-sm"></i>
                        </button>
                    </a>
                    <form action="{{ route('discounts.destroy', $discount->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDeleteDiscounts(event, this.closest('form'))" class="btn btn-delete bg-gray-50 border border-gray-300 hover:bg-red-500 text-white font-bold rounded-full p-2 w-10 h-10 flex items-center justify-center">
                            <i class="text-sm fa-solid fa-trash text-black"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>



    <script>
function confirmDeleteDiscounts(event, form) {
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


    <script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput-discounts');
    const brandCardsContainers = document.querySelectorAll('.discount-cards-container');
    const noResultsDiv = document.getElementById('noResults-discounts');
    const titles = document.querySelectorAll('.title');

    searchInput.addEventListener('input', () => {
        const searchTerm = searchInput.value.toLowerCase();
        let totalVisibleCards = 0;

        brandCardsContainers.forEach((container, index) => {
            const cards = container.querySelectorAll('.card');
            let visibleCardsInContainer = 0;
            
            cards.forEach(card => {
                const brandName = card.querySelector('h5').textContent.toLowerCase();
                if (brandName.includes(searchTerm)) {
                    card.style.display = '';
                    visibleCardsInContainer++;
                } else {
                    card.style.display = 'none';
                }
            });

            totalVisibleCards += visibleCardsInContainer;

            if (visibleCardsInContainer > 0) {
                titles[index].style.display = '';
            } else {
                titles[index].style.display = 'none';
            }
        });

        if (totalVisibleCards === 0) {
            noResultsDiv.style.display = 'block';
        } else {
            noResultsDiv.style.display = 'none';
        }
    });
});
</script>

</x-app-layout>
