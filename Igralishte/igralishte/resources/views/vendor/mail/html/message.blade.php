<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
{{ config('app.name') }}
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{{ $slot }}


{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
<span class="text-xs text-gray-600 px-5">Сите права задржани @ Игралиште Скопје</span>
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
