@include('partials.turniej-nav')

{{-- zawartość --}}
<x-layouts::app :title="$turniej->nazwa" >
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <h1 class="text-2xl font-bold tracking-tight">{{ $turniej->nazwa }}</h1>

        {{-- organizator --}}
        <p class="text-white flex items-center gap-3 whitespace-nowrap overflow-x-auto">
            <span class="inline-flex items-center gap-1"><flux:icon.user-circle class="text-main"/> Organizator: {{ $turniej->organizator->name }}</span>
        </p>
        {{-- miejsce --}}
        <p class="text-white flex items-center gap-3 whitespace-nowrap overflow-x-auto">
            <span class="inline-flex items-center gap-1"><flux:icon.map class="text-main"/> {{ $turniej->miejsce }}</span>
        </p>
        {{-- daty --}}
        <p class="text-white flex items-center gap-3 whitespace-nowrap overflow-x-auto">
            <span class="inline-flex items-center gap-1"><flux:icon.calendar class="text-main"/> {{ $turniej->data_rozpoczecia }} - {{ $turniej->data_zakonczenia }}</span>
        </p>
        {{-- liczba rund --}}
        <p class="text-white flex items-center gap-3 whitespace-nowrap overflow-x-auto">
            <span class="inline-flex items-center gap-1"><flux:icon.book-open class="text-main"/> {{ $turniej->liczba_rund }} rund</span>
        </p>
        {{-- liczba zawodników --}}
        <p class="text-white flex items-center gap-3 whitespace-nowrap overflow-x-auto">
            <span class="inline-flex items-center gap-1"><flux:icon.user-group class="text-main"/> {{ $turniej->liczba_zawodnikow }} / {{ $turniej->limit_zawodnikow }} zawodników</span>
        </p>
        {{-- tempo gry --}}
        <p class="text-white flex items-center gap-3 whitespace-nowrap overflow-x-auto">
            <span class="inline-flex items-center gap-1"><flux:icon.clock class="text-main"/>{{ $turniej->tempo_gry }}</span>
        </p>

        {{-- status --}}
        <p class="text-white flex items-center gap-3 whitespace-nowrap overflow-x-auto">
            <span class="inline-flex items-center gap-1"><flux:icon.forward class="text-main"/>{{ $turniej->status->nazwa }}</span>
        </p>
        {{-- opis --}}
        @if($turniej->opis != null)
            <p class="text-white flex gap-3 overflow-x-auto">
                <span class="inline-flex gap-1"><flux:icon.chat-bubble-bottom-center-text class="text-main"/> {{ $turniej->opis }}</span>
            </p>
        @endif
        {{-- komunikat --}}
        @if($turniej->komunikat_path)
            <p class="text-white flex gap-3 overflow-x-auto">
                <span class="inline-flex gap-1"><flux:icon.document class="text-main"/>
                    <a href="{{ Storage::url($turniej->komunikat_path) }}" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 rounded-md border border-main/40 bg-main/10 px-3 py-2 text-sm font-medium text-main hover:bg-main/20">
                        <flux:icon.arrow-down-tray class="text-main" />
                        Pobierz komunikat
                    </a>
                </span>
            </p>

        @endif

    </div>
</x-layouts::app>
