<x-app-layout>
<x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="flex items-center justify-center min-h-screen mx-auto">
        
        <div class="max-w-md w-full bg-white rounded-lg p-6">
        <div class="flex items-center justify-start pb-10">
    <a href="{{ route('admin.profile') }}"><i class="fa-solid fa-arrow-left text-2xl"></i></a><span class="text-xs md:text-2xl font-bold ml-4">Назад</span>
</div>
            
            <h1 class="text-2xl font-bold">Променете ја вашата лозинка</h1>
            <form action="{{ route('password.new') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="password" class="block text-sm font-semibold text-black">Нова лозинка</label>
                    <input type="password" name="password" id="password"
                        class="font-normal text-gray-500 block mt-1 w-full bg-transparent border border-gray-400 rounded-lg py-2 px-4 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-black">Потврди нова
                        лозинка</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="font-normal text-gray-500 block mt-1 w-full bg-transparent border border-gray-400 rounded-lg py-2 px-4 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500">
                </div>

                <button type="submit"
                    class="w-full text-xl text-white bg-black hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-bold rounded-xl text-md px-5 py-3.5 me-2 mb-2 dark:bg-black dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
                    Промени лозинка
                </button>
            </form>


            @if (session('success'))
                <div class="mb-4 text-green-600">
                    {{ session('success') }}
                </div>
                @endif

                @error('password')
                <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
        </div>
    </div>


</x-app-layout>