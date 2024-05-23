<x-guest-layout>
<script src="//unpkg.com/alpinejs@2.8.2/dist/alpine.js" defer></script>
<style>
.screen-reader-only {
    position: absolute;
    left: -10000px;
    top: auto;
    width: 1px;
    height: 1px;
    overflow: hidden;
}

.checkbox-container {
    display: inline-block;
    font-family: Arial, sans-serif;
    user-select: none;
    cursor: pointer;
}

.checkbox {
    display: inline-block;
    width: 24px;
    height: 24px;
    background-color: #f0f0f0;
    border-radius: 5px;
    margin-right: 8px;
    position: relative;
    vertical-align: middle;
    border: 2px solid #d1d1d1;
    transition: background-color 0.3s, border-color 0.3s;
}

.checkbox.checked {
    background-color: #4CAF50;
    border-color: #4CAF50;
}

.checkbox-icon {
    color: white;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 16px;
    opacity: 0;
    transition: opacity 0.3s;
}

.checkbox.checked .checkbox-icon {
    opacity: 1;
}

</style>
    <form method="POST" action="{{ route('register.process.step.two') }}">
        @csrf

        <!-- First and Last Name -->
        <div>
            <label class="font-semibold text-black" for="first_name">Име</label>
            <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}"  autofocus
                autocomplete="first_name"
                class="font-normal text-gray-500 block mt-1 w-full bg-transparent border border-gray-400 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <div class="mt-5">
            <label class="font-semibold text-black" for="last_name">Презиме</label>
            <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" autofocus
                autocomplete="last_name"
                class="font-normal text-gray-500 block mt-1 w-full bg-transparent border border-gray-400 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-5">
            <label class="font-semibold text-black" for="email">Email адреса</label>
            <input id="email"
                class="font-normal text-gray-500 block mt-1 w-full bg-transparent border border-gray-400 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                type="email" name="email" value="{{ session('registration.email') }}" autofocus autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label class="font-semibold text-black" for="password">Лозинка</label>
            <input id="password"
                class="font-normal text-gray-500 block mt-1 w-full bg-transparent border border-gray-400 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                type="password" name="password" value="{{ session('registration.password') }}" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <label class="font-semibold text-black" for="password_confirmation">Повтори лозинка</label>
            <input id="password_confirmation"
                class="font-normal text-gray-500 block mt-1 w-full bg-transparent border border-gray-400 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                type="password" name="password_confirmation" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>


        <div class="mt-4 pb-6">


                <div x-data="{ checked: false }" class="checkbox-container">
                    <label for="wants_promo_emails">
                        <input type="checkbox" id="wants_promo_emails" name="wants_promo_emails" value="1" {{ old('wants_promo_emails') ? 'checked' : '' }} x-model="checked" class="screen-reader-only" /> <!-- Hidden actual checkbox -->
                        <div class="checkbox" :class="{'checked': checked}">
                            <div class="checkbox-icon" x-show="checked">
                                ✓
                            </div>
                        </div>
                        Испраќај ми известувања за нови зделки и промоции.
                    </label>
                </div>

   
        </div>


        <button type="submit"
            class="w-80 md:w-full text-xl text-white bg-black hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-bold rounded-xl text-md px-5 py-3.5 me-2 mb-2 dark:bg-black dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
            {{ __('Регистрирај се') }}
        </button>
    </form>


    <div class="flex justify-start text-small pb-5 mb-5 pt-6 mt-5 mx-0 px-0">
            <span class="text-xs text-gray-600 mx-0 px-0">Со вашата регистрација, се согласувате со <a href="/rules" class="underline text-black">Правилата и Условите</a> за коснички сајтови.</span>
        </div>

    <script>
        gsap.registerPlugin(CSSPlugin);

document.getElementById('wants_promo_emails').addEventListener('change', function(e) {
    const isChecked = e.target.checked;
    const checkboxAnimationTarget = document.querySelector('.custom-checkbox-animation');

    if (isChecked) {
        gsap.to(checkboxAnimationTarget, { duration: 0.5, scale: 1.2, backgroundColor: "#4CAF50", ease: "elastic.out(1, 0.3)" });
    } else {
        gsap.to(checkboxAnimationTarget, { duration: 0.5, scale: 1, backgroundColor: "#f2f2f2", ease: "elastic.out(1, 0.3)" });
    }
});

    </script>
</x-guest-layout>
