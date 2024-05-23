<x-guest-layout>

    <form method="POST" action="{{ route('register.process.step.three') }}" enctype="multipart/form-data" id="registrationFormStepThree">
        @csrf


                <div class="flex justify-center items-center">
            <div class="flex-col">
                <div id="img-div" class="md:w-64 rounded-full overflow-hidden bg-gray-100 mb-5" style="display: none;">
                    <!-- Image hidden initially -->
                    <img id="profilePhotoPreview2" src="{{ asset('images/default-profile.png') }}" alt="Profile Photo" style="display: none;">
                </div>
                <div class="flex justify-center items-center">
                <button type="button" onclick="document.getElementById('profile_photo2').click()"
                        class="font-small text-sm text-black hover:text-gray-900 rounded-full bg-gray-300 px-2 py-1">
                    Одбери слика
                </button>
                <input type="file" id="profile_photo2" name="profile_photo" class="hidden" onchange="previewProfilePhoto2()">
                </div>
            </div>
        </div>


        <!-- Address -->
        <div class="mt-4">
            <label class="font-semibold text-black" for="address" :value="__('Address')" />Адреса
            <input id="address"
                class="font-normal text-gray-500 block mt-1 w-full bg-transparent border border-gray-400 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                type="text" name="address" :value="old('address')" />
        </div>

        <!-- Phone Number -->
        <div class="mt-4">
            <label class="font-semibold text-black" for="phone_number" :value="__('Phone Number')" />Телефонски број
            <input id="phone_number"
                class="font-normal text-gray-500 block mt-1 w-full bg-transparent border border-gray-400 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                type="text" name="phone_number" :value="old('phone_number')" />
        </div>

        <!-- Bio -->
        <div class="mt-4">
            <label class="font-semibold text-black" for="bio" :value="__('Bio')" />Биографија
            <textarea id="bio" name="bio" rows="3" {{ old('bio') }}
                class="font-normal text-gray-500 block mt-1 w-full bg-transparent border border-gray-400 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500"></textarea>
        </div>

        <button type="submit"
                class="mt-5 w-full text-xl text-white bg-black hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-bold rounded-xl text-md px-5 py-3.5 me-2 mb-2 dark:bg-black dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
            {{ __('Заврши') }}
        </button>


        <button id="skipButton" type="button" onclick="window.location=`{{ route('register.skip.step.three') }}` " class="text-sm font-normal text-black underline pt-5">
                {{ __('Прескокни') }}
        </button>


    <script>
function previewProfilePhoto2() {
    var input = document.getElementById('profile_photo2');
    var preview = document.getElementById('profilePhotoPreview2');
    var imgDiv = document.getElementById('img-div');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            imgDiv.style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('finishButton').addEventListener('click', function() {
        var form = document.getElementById('registrationFormStepThree');
        form.submit();
    });

});
</script>
</x-guest-layout>