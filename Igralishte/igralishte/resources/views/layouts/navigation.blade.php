<script src="//unpkg.com/alpinejs" defer></script>

<div x-data="{ open: false }" class="min-h-screen flex">

<div :class="open ? 'fixed inset-0 z-40 w-full' : 'w-22 h-screen'" class="bg-gray-100 transition-all duration-900 ease-in-out overflow-hidden">

<div class="overflow-hidden h-screen flex flex-col justify-between transition-all duration-100 ease-in-out">
<button @click="open = false" x-show="open" x-cloak class="absolute top-0 right-0 m-2 text-4xl text-gray-600 hover:text-gray-800">&times;</button>

        <!-- Profile Picture & Icons -->
        <div>
                <!-- Profile Picture and User Information -->
                <div class="ml-5 flex items-center">
                    <img @click="open = !open"
                        src="{{ auth()->check() ? asset('storage/' . auth()->user()->profile_photo) : asset('default-image-path.jpg') }}"
                        alt="Profile" class="h-12 w-12 rounded-full my-4 cursor-pointer">   

                    <!-- User Name and Email -->
                    <div x-show="open" class="ml-4" x-cloak>
                        @if(auth()->check())
                            <p class="font-bold text-black">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                            <p class="text-sm text-gray-400">{{ auth()->user()->email }}</p>
                        @endif
                    </div>
                </div>


            <a href="{{ route('products') }}" class="flex items-center w-full">
                <div :class="open ? 'justify-start' : 'justify-center'"
                    class="mx-5 transition-colors duration-150 {{ request()->routeIs('products') ? 'bg-custom-pink text-black' : 'hover:bg-custom-pink focus:bg-custom-pink active:bg-custom-pink text-gray-500 hover:text-black focus:text-black active:text-black' }} p-2 mb-1 rounded-lg w-full flex items-center cursor-pointer">
                    <i class="fa-solid fa-gear text-2xl p-1"></i>
                    <span x-show="open" class="ml-2 font-bold">Vintage облека</span>
                </div>
            </a>

            <a href="{{ route('discounts') }}" class="flex items-center w-full">
                <div :class="open ? 'justify-start' : 'justify-center'"
                    class="mx-5 transition-colors duration-150 {{ request()->routeIs('discounts') ? 'bg-custom-pink text-black' : 'hover:bg-custom-pink focus:bg-custom-pink active:bg-custom-pink text-gray-500 hover:text-black focus:text-black active:text-black' }} p-2 my-1 rounded-lg w-full flex items-center cursor-pointer">
                    <i class="fa-solid fa-percent text-2xl p-1"></i>
                    <span x-show="open" class="ml-2 font-bold pl-1">Попусти / промо</span>
                </div>
            </a>

            <a href="{{ route('brands') }}" class="flex items-center w-full">
                <div :class="open ? 'justify-start' : 'justify-center'"
                    class="mx-5 transition-colors duration-150 {{ request()->routeIs('brands') ? 'bg-custom-pink text-black' : 'hover:bg-custom-pink focus:bg-custom-pink active:bg-custom-pink text-gray-500 hover:text-black focus:text-black active:text-black' }} p-2 rounded-lg w-full flex items-center cursor-pointer">
                    <i class="fa-solid fa-tags text-2xl p-1"></i>
                    <span x-show="open" class="ml-2 font-bold">Брендови</span>
                </div>
            </a>


            <a href="{{ route('admin.profile') }}" class="flex items-center w-full">
                <div :class="open ? 'justify-start' : 'justify-center'"
                    class="mx-5 my-1 transition-colors duration-150 {{ request()->routeIs('admin.profile') ? 'bg-custom-pink text-black' : 'hover:bg-custom-pink focus:bg-custom-pink active:bg-custom-pink text-gray-500 hover:text-black focus:text-black active:text-black' }} p-2 rounded-lg w-full flex items-center cursor-pointer">
                    <i class="fa-solid fa-user text-2xl p-1"></i>
                    <span x-show="open" class="ml-2 font-bold pl-1">Профил</span>
                </div>
            </a>


        </div>

        <div @click="open = !open" class="p-2 ml-5 mr-6 pb-5 flex items-center justify-start cursor-pointer">
            <i class="ml-1 fa-solid fa-arrow-right-from-bracket text-2xl"></i>
            <form method="POST" action="{{ route('logout') }}" x-ref="logoutForm">
                @csrf
                <a href="#" @click="if (open) $refs.logoutForm.submit();" class="ml-2 font-bold flex hover:bg-custom-pink focus:bg-custom-pink px-5 rounded-lg"
                    x-show="open">Одјави се</a>
            </form>
        </div>

        </div>

    </div>
    <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-1000" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-30" @click="open = false"></div>


    <!-- Page Content -->
    <div :class="{'ml-64': open, 'ml-22': !open}" class="transition-all duration-1000 ease-in-out flex-1 overflow-y-auto" style="height: 100vh;">
        {{ $slot }}
</div>

    
</div>
