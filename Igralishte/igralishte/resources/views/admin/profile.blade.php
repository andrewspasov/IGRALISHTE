<x-app-layout>
    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="mt-2 md:mt-0 md:col-span-2">
                <div>
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <h1 class="font-bold text-xl">Мој профил</h1>
                        <div>
                            <div class="flex-col">
                                <div class="w-1/2 md:w-20 rounded-full overflow-hidden bg-gray-100">
                                    <img id="profilePhotoPreview" src="{{ asset('storage/' . $user->profile_photo) }}"
                                        alt="Profile Photo">
                                </div>
                                <button id="forgot-pw" type="button"
                                    onclick="document.getElementById('profile_photo').click()"
                                    class="underline font-medium text-sm hover:text-gray-900 rounded-md">
                                    Промени слика
                                </button>
                                <input type="file" id="profile_photo" name="profile_photo" class="hidden"
                                    onchange="previewProfilePhoto()">
                            </div>
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <label for="first_name" class="font-semibold block text-sm text-black">Име</label>
                            <input type="text" name="first_name" id="first_name" value="{{ $user->first_name }}" autocomplete="name"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 text-gray-500 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500">
                            @error('first_name')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <label for="last_name" class="font-semibold block text-sm text-black">Презиме</label>
                            <input type="text" name="last_name" id="last_name" value="{{ $user->last_name }}" autocomplete="last_name"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 text-gray-500 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500">
                            @error('last_name')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <label for="email" class="block text-sm font-semibold text-black">Email адреса</label>
                            <input type="text" name="email" id="email" value="{{ $user->email }}" autocomplete="email"
                                class="mt-1 block w-full shadow-sm sm:text-sm text-gray-500 border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500">
                            @error('email')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <label for="phone_number" class="block text-sm font-semibold text-black">Телефонски
                                број</label>
                            <input type="text" name="phone_number" id="phone_number" value="{{ $user->phone_number }}"
                                autocomplete="tel"
                                class="mt-1 block w-full text-gray-500 shadow-sm sm:text-sm border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500">
                            @error('phone_number')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <label for="password" class="block text-sm font-semibold text-black">Лозинка</label>
                            <input type="password" name="password" id="password" value="password"
                                class="mb-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-gray-500" disabled
                                placeholder="************">

                            <a id="forgot-pw" class="underline font-medium text-sm hover:text-gray-900 rounded-md"
                                href="{{ route('password.newShow') }}">
                                {{ __('Промени лозинка') }}
                            </a>
                        </div>
                        <button type="submit"
                            class="w-full md:w-1/2 text-xl text-white bg-black hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-bold rounded-xl text-md px-5 py-3.5 me-2 mb-2 dark:bg-black dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
                            Зачувај
                        </button>

                        @if (session('success'))
                        <div class="mb-4 text-green-600">
                            {{ session('success') }}
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </form>

</x-app-layout>