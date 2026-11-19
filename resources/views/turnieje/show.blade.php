@php
    $currentRoute = Route::currentRouteName();
@endphp

{{-- zawartość --}}
<x-layouts::app :title="$turniej->nazwa">
    @if (session('error'))
        <div class="p-2 m-4 flex items-center justify-between whitespace-nowrap overflow-x-auto glass-2">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="p-2 m-4 flex items-center justify-between whitespace-nowrap overflow-x-auto glass-2">
            {{ session('success') }}
        </div>
    @endif

    <div class="p-4 flex w-full flex-1 flex-col">

        <div class="grid w-full grid-cols-5">
            <a href="{{ route('turnieje.show', $turniej) }}" class="glass p-2" style="border-bottom: none">
                Komunikat
            </a>
            <a href="{{ route('zawodnicy.index', [$turniej]) }}" class="glass-off p-2">
                Wyniki
            </a>
            <a href="{{ route('rundy.show', $turniej) }}" class="glass-off p-2">
                Rundy
            </a>
            <a href="{{ route('turnieje.edit', $turniej) }}" class="glass-off p-2">
                Edytuj turniej
            </a>
            <a href="{{ route('turnieje.zgloszenia.index', $turniej) }}" class="glass-off p-2">
                Zgłoszenia
            </a>
        </div>
        <div class="p-4 flex w-full flex-1 flex-col glass" style="border-top: none;">

            <h1 class="m-2 p-2 text-2xl font-bold tracking-tight">{{ $turniej->nazwa }}</h1>

            {{-- ======================== Informacje ogólne ========================= --}}
            <h2 class="m-2 p-2 text-l text-gray-300 tracking-tight">Informacje ogólne</h2>
            <div class="grid grid-cols-2">
                {{-- organizatorzy --}}
                <p class="p-2 m-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span
                        class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.user-circle
                            class="text-main" /> Organizatorzy</span>
                    <span class="inline-flex items-center gap-1">{{ $turniej->organizator?->name ?? 'Brak danych' }}</span>
                </p>

                {{-- miejsce --}}
                <p class="p-2 m-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                        <flux:icon.map class="text-main" /> Miejsce
                    </span>
                    <span class="inline-flex items-center gap-1">{{ $turniej->miejsce }}</span>
                </p>
                {{-- daty --}}
                <p class="p-2 m-2 grid  items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                        <flux:icon.calendar class="text-main" /> Data
                    </span>
                    <span class="inline-flex items-center gap-1">
                        {{ \Illuminate\Support\Carbon::parse($turniej->data_rozpoczecia)->format('d.m.Y') }} -
                        {{ \Illuminate\Support\Carbon::parse($turniej->data_zakonczenia)->format('d.m.Y') }}
                    </span>
                </p>
                {{-- liczba rund --}}
                <p class="p-2 m-2 grid  items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span
                        class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.book-open
                            class="text-main" /> Liczba rund</span>
                    <span class="inline-flex items-center gap-1">{{ $turniej->liczba_rund }}</span>
                </p>

                {{-- tempo gry --}}
                <p class="p-2 m-2 grid  items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                        <flux:icon.clock class="text-main" />Tempo gry
                    </span>
                    <span class="inline-flex items-center gap-1">{{ $turniej->tempo_gry }}</span>
                </p>

                {{-- rodzaj szachów --}}
                <p class="p-2 m-2 grid  items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                        <flux:icon.clock class="text-main" />Rodzaj szachów
                    </span>
                    <span class="inline-flex items-center gap-1">{{ $turniej->rodzaj_szachow->label() }}</span>
                </p>

                {{-- liczba zawodników --}}
                <p class="p-2 m-2 grid  items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span
                        class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.user-group
                            class="text-main" /> Liczba zawodników</span>
                    <span class="inline-flex items-center gap-1">{{ $turniej->liczba_zawodnikow }} /
                        {{ $turniej->limit_zawodnikow }}</span>
                </p>

                {{-- status --}}
                <p class="p-2 m-2 grid  items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                        <flux:icon.forward class="text-main" />Status
                    </span>
                    <span class="inline-flex items-center gap-1">{{ $turniej->status->nazwa }}</span>
                </p>
            </div>

            {{-- ======================== Informacje dodatkowe ========================= --}}
            <h2 class="m-2 p-2 text-l text-gray-300 tracking-tight">Informacje dodatkowe</h2>
            <div class="grid grid-cols-2">
                {{-- Zgłoszony do PZSzach --}}
                <p class="p-2 m-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span
                        class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.document-text
                            class="text-main" />Zgłoszony do PZSzach</span>
                    <span
                        class="inline-flex items-center gap-1">{{ $turniej->zgloszony_do_pzszach ? 'Tak' : 'Nie' }}</span>
                </p>

                {{-- Zgłoszony do FIDE --}}
                <p class="p-2 m-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span
                        class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.document-text
                            class="text-main" />Zgłoszony do FIDE</span>
                    <span
                        class="inline-flex items-center gap-1">{{ $turniej->zgloszony_do_fide ? 'Tak' : 'Nie' }}</span>
                </p>
            </div>

            <div class="grid grid-cols-1">

                {{-- opis --}}
                @if ($turniej->opis != null)
                    <p class="p-2 m-2 grid overflow-x-auto glass-2 ">
                        <span
                            class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.chat-bubble-bottom-center-text
                                class="text-main" /> Opis</span>
                        <span class="inline-flex items-center gap-1"> {{ $turniej->opis }}</span>
                    </p>
                @endif
                {{-- komunikat --}}
                @if ($turniej->komunikat_path)
                    <p class="p-2 m-2 grid overflow-x-auto glass-2 ">
                        <span class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.document class="text-main" /> Komunikat
                        </span>
                        <span class="inline-flex items-center gap-1 pt-1">
                            <a href="{{ Storage::url($turniej->komunikat_path) }}" target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 rounded-md bg-black/20 px-3 py-2 text-sm font-medium hover:bg-white hover:text-black duration-200">
                                <flux:icon.arrow-down-tray />
                                Pobierz komunikat
                            </a>
                        </span>
                    </p>
                @endif
            </div>

                        {{-- ======================= Ograniczenia i zgłaszanie się ========================== --}}
            <h2 class="m-2 p-2 text-l text-gray-300 tracking-tight">Ograniczenia</h2>
            <div class="grid grid-cols-5">
                {{-- ograniczenia - płeć --}}
                <p class="p-2 m-2 grid  items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                        <flux:icon.question-mark-circle class="text-main" />Ograniczenie do płci
                    </span>
                    <span
                        class="inline-flex items-center gap-1">
                        {{ ['M' => 'Mężczyźni', 'K' => 'Kobiety', 'B' => 'Bez ograniczeń'][$turniej->limit_plec] ?? 'Brak ograniczeń' }}
                    </span>
                </p>

                {{-- ograniczenia - wiek --}}
                <p class="p-2 m-2 grid  items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                        <flux:icon.question-mark-circle class="text-main" />Ograniczenie wiekowe
                    </span>
                    <span
                        class="inline-flex items-center gap-1">{{ $turniej->limit_wiek !== null ? $turniej->limit_wiek . ' lat' : 'Brak ograniczeń' }}</span>
                </p>

                {{-- ograniczenia - ranking --}}
                <p class="p-2 m-2 grid  items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                        <flux:icon.question-mark-circle class="text-main" />Ograniczenie rankingowe
                    </span>
                    <span class="inline-flex items-center gap-1">
                        @if ($turniej->limit_ranking_od !== null && $turniej->limit_ranking_do !== null)
                            {{ $turniej->limit_ranking_od }} - {{ $turniej->limit_ranking_do }}
                        @elseif ($turniej->limit_ranking_od !== null)
                            Od {{ $turniej->limit_ranking_od }}
                        @elseif ($turniej->limit_ranking_do !== null)
                            Do {{ $turniej->limit_ranking_do }}
                        @else
                            Brak ograniczeń
                        @endif
                    </span>
                </p>

                {{-- ograniczenia - klub --}}
                <p class="p-2 m-2 grid  items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                        <flux:icon.question-mark-circle class="text-main" />Turniej klubowy
                    </span>
                    <span
                        class="inline-flex items-center gap-1">{{ $turniej->limit_klub ? 'Tak' : 'Nie' }}</span>
                </p>

                <form action="{{ route('turnieje.zgloszenia.store', $turniej) }}" method="POST" enctype="multipart/form-data" class="m-2">
                    @csrf
                    @method('POST')

                    @if($zgloszenie != null && $zgloszenie->status_id == 1)
                        <input type="submit" disabled value="Zgłoszenie oczekuje na potwierdzenie" class="w-full h-full items-center whitespace-wrap bg-main/20 " />
                    @elseif($zgloszenie != null && $zgloszenie->status_id == 2)
                        <input type="submit" disabled value="Już uczestniczysz w tym turnieju" class="w-full h-full items-center whitespace-wrap bg-main/20 " />
                    @else
                        <input type="submit" value="Zgłoś się" class="w-full h-full p-0 m-0 grid  items-center whitespace-nowrap overflow-x-auto bg-main cursor-pointer hover:bg-main/80 duration-200" />
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-layouts::app>
