@include('partials.turniej-nav')

{{-- zawartość --}}
<x-layouts::app :title="$turniej->nazwa">
    <div class="p-4 flex w-full flex-1 flex-col glass">

        <h1 class="m-2 p-2 text-2xl font-bold tracking-tight">{{ $turniej->nazwa }}</h1>

        <div class="grid grid-cols-2">
            {{-- organizatorzy --}}
            <p class="p-2 m-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                <span class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.user-circle class="text-main"/> Organizatorzy</span>
                <span class="inline-flex items-center gap-1">{{ $turniej->organizator->name }}</span>
            </p>
            {{-- sędziowie --}}
            <p class="p-2 m-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                <span class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.user-circle class="text-main"/> Sędziowie</span>
                <span class="inline-flex items-center gap-1">XXX</span>
            </p>
            {{-- miejsce --}}
            <p class="p-2 m-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                <span class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.map class="text-main"/> Miejsce</span>
                <span class="inline-flex items-center gap-1">{{ $turniej->miejsce }}</span>
            </p>
            {{-- daty --}}
            <p class="p-2 m-2 grid  items-center whitespace-nowrap overflow-x-auto glass-2 ">
                <span class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.calendar class="text-main"/> Data </span>
                <span class="inline-flex items-center gap-1">{{ $turniej->data_rozpoczecia }} - {{ $turniej->data_zakonczenia }}</span>
            </p>
            {{-- liczba rund --}}
            <p class="p-2 m-2 grid  items-center whitespace-nowrap overflow-x-auto glass-2 ">
                <span class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.book-open class="text-main"/> Liczba rund</span>
                <span class="inline-flex items-center gap-1">{{ $turniej->liczba_rund }}</span>
            </p>
            {{-- liczba zawodników --}}
            <p class="p-2 m-2 grid  items-center whitespace-nowrap overflow-x-auto glass-2 ">
                <span class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.user-group class="text-main"/> Liczba zawodników</span>
                <span class="inline-flex items-center gap-1">{{ $turniej->liczba_zawodnikow }} / {{ $turniej->limit_zawodnikow }}</span>
            </p>
            {{-- tempo gry --}}
            <p class="p-2 m-2 grid  items-center whitespace-nowrap overflow-x-auto glass-2 ">
                <span class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.clock class="text-main"/>Tempo gry</span>
                <span class="inline-flex items-center gap-1">{{ $turniej->tempo_gry }}</span>
            </p>

            {{-- status --}}
            <p class="p-2 m-2 grid  items-center whitespace-nowrap overflow-x-auto glass-2 ">
                <span class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.forward class="text-main"/>Status</span>
                <span class="inline-flex items-center gap-1">{{ $turniej->status->nazwa }}</span>
            </p>
        </div>

            {{-- opis --}}
            @if($turniej->opis != null)
                <p class="p-2 m-2 grid overflow-x-auto glass-2 ">
                    <span class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.chat-bubble-bottom-center-text class="text-main"/> Opis</span>
                    <span class="inline-flex items-center gap-1"> {{ $turniej->opis }}</span>
                </p>
            @endif
            {{-- komunikat --}}
            @if($turniej->komunikat_path)
                <p class="p-2 m-2 grid overflow-x-auto glass-2 ">
                    <span class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.document class="text-main"/> Komunikat</span>
                    <span class="inline-flex items-center gap-1 pt-1">
                        <a href="{{ Storage::url($turniej->komunikat_path) }}" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 rounded-md bg-black/20 px-3 py-2 text-sm font-medium hover:bg-white hover:text-black duration-200">
                            <flux:icon.arrow-down-tray />
                            Pobierz komunikat
                        </a>
                    </span>
                </p>

            @endif
    </div>
</x-layouts::app>
