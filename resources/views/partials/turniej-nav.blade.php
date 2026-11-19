{{-- zakładki --}}
@php
    $currentRoute = Route::currentRouteName();
@endphp

<nav class="px-4 py-2 flex items-center bg-main">
    <a href="{{ route('turnieje.show', $turniej) }}">
        <button class="px-4 py-2 text-md font-medium {{ $currentRoute === 'turnieje.show' ? 'text-white' : 'text-gray-300' }} hover:text-white hover:cursor-pointer">Komunikat</button>
    </a>

    <a href="{{ route('zawodnicy.index', [$turniej]) }}">
        <button class="px-4 py-2 text-md font-medium {{ $currentRoute === 'zawodnicy.index' ? 'text-white' : 'text-gray-300' }} hover:text-white hover:cursor-pointer">Wyniki</button>
    </a>
    <a href="{{ route('rundy.show', $turniej) }}">
        <button class="px-4 py-2 text-md font-medium {{ $currentRoute === 'rundy.show' ? 'text-white' : 'text-gray-300' }} hover:text-white hover:cursor-pointer">Rundy</button>
    </a>
    <button class="px-4 py-2 text-md font-medium {{ $currentRoute === 'turnieje.show' ? 'text-gray-300' : 'text-gray-300' }} hover:text-white hover:cursor-pointer">Generuj</button>
    <a href="{{ route('turnieje.edit', $turniej) }}">
        <button class="px-4 py-2 text-md font-medium {{ $currentRoute === 'turnieje.edit' ? 'text-white' : 'text-gray-300' }} hover:text-white hover:cursor-pointer">Edytuj turniej</button>
    </a>
    <a href="{{ route('turnieje.zgloszenia.index', $turniej) }}">
        <button class="px-4 py-2 text-md font-medium {{ $currentRoute === 'turnieje.zgloszenia.index' ? 'text-white' : 'text-gray-300' }} hover:text-white hover:cursor-pointer">Zgłoszenia</button>
    </a>
    <a href="{{ route('turnieje.zgloszenia.create', $turniej) }}">
        <button class="px-4 py-2 text-md font-medium {{ $currentRoute === 'turnieje.zgloszenia.create' ? 'text-white' : 'text-gray-300' }} hover:text-white hover:cursor-pointer">Zgłoś się</button>
    </a>
</nav>