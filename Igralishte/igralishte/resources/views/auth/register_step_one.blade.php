<x-guest-layout>
    <!-- Session Status -->
    <script src="https://kit.fontawesome.com/f1ee2d6aa9.js" crossorigin="anonymous"></script>

    <form method="POST" action="{{ route('register.process.step.one') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label class="font-semibold text-black" for="email">Email адреса</label>
            <input id="email"
                class="font-normal text-gray-500 block mt-1 w-full bg-transparent border border-gray-400 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                type="email" name="email" value="{{ old('email') }}" autofocus autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4 mb-5">
            <label class="font-semibold text-black" for="password">Лозинка</label>
            <input id="password"
                class="font-normal text-gray-500 block mt-1 w-full bg-transparent border border-gray-400 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                type="password" name="password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>


        <button
            class="w-full text-xl text-white bg-black hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-bold rounded-xl text-md px-5 py-3.5 me-2 mb-2 dark:bg-black dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
            {{ __('Продолжи') }}
        </button>

        <div class="mt-4">
            <a href="{{ route('auth.google') }}"
                class="block text-start bg-transparent border-2 border-pink-200 text-black font-semibold py-2 px-4 rounded-xl">
                <div class="text-xs flex justify-start items-center mx-5"><i class="fa-brands fa-google mr-2 text-xl"></i> <span>Регистрирај се преку Google</span></div>
            </a>
        </div>

        <div class="mt-4">
            <a href="{{ route('auth.facebook') }}"
                class="block text-start bg-transparent border-2 border-pink-200 text-black font-semibold py-2 px-4 rounded-xl">
                <div class="text-xs flex justify-start items-center mx-5"><i class="fa-brands fa-facebook mr-2 text-xl"></i> <span>Регистрирај се преку Facebook</span></div>
            </a>
        </div>

        <div class="flex items-center justify-start mb-6 mt-1 py-5">
            <p class="font-normal text-sm mr-1">Веќе имаш профил?</p>
            <a id="forgot-pw"
                class="underline font-medium text-sm hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('Најави се') }}
            </a>
        </div>
    </form>

    <div class="flex justify-center items-center text-small pb-5 mb-5 pt-6 mt-5">
            <span class="text-xs text-gray-600 px-5">Сите права задржани @ Игралиште Скопје</span>
        </div>
</x-guest-layout>
