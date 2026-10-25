{{-- zawartość --}}
<x-layouts::app :title="$zawodnik->name">

    <div class="p-4 flex w-full flex-1 flex-col">
        <div class="grid w-full grid-cols-4">
            <a href="{{ route('zawodnicy.show', $zawodnik) }}" class="glass p-2" style="border-bottom: none">Profil</a>
            <a href="#" class="glass-off p-2">Historia rankingu</a>
            <a href="#" class="glass-off p-2">Historia turniejów</a>
            <a href="{{ route('tytuly.index', $zawodnik) }}" class="glass-off p-2">Historia tytułów</a>
        </div>
        
        <div class="p-4 flex w-full flex-1 flex-col glass" style="border-top: none;">

            <h1 class="m-2 p-2 text-2xl font-bold tracking-tight">{{ $zawodnik->name }}</h1>
            <h2 class="m-2 p-2 pt-0 mt-0 text-l text-gray-300 tracking-tight">Profil użytkownika</h2>

            <div class="grid grid-cols-2">
                {{-- Data urodzenia --}}
                <p class="p-2 m-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span
                        class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.cake
                            class="text-main" /> Data urodzenia</span>
                    <span class="inline-flex items-center gap-1">{{ $zawodnik->data_urodzenia }}</span>
                </p>

                {{-- Płeć --}}
                <p class="p-2 m-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span
                        class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.identification
                            class="text-main" /> Płeć</span>
                    <span
                        class="inline-flex items-center gap-1">{{ $zawodnik->plec == 'M' ? 'Mężczyzna' : 'Kobieta' }}</span>
                </p>

                {{-- Email --}}
                <p class="p-2 m-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span
                        class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.envelope
                            class="text-main" />Email</span>
                    <span
                        class="inline-flex items-center gap-1">{{ $zawodnik->email }}</span>
                </p>

                {{-- Tytul --}}
                <p class="p-2 m-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span
                        class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.trophy
                            class="text-main" />Tytuł</span>
                    <span
                        class="inline-flex items-center gap-1">{{ $tytul_top != null ? $tytul_top->nazwa : 'Brak' }}</span>
                </p>

                {{-- Ranking PZSzach --}}
                <p class="p-2 m-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span
                        class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.academic-cap
                            class="text-main" />Ranking PZSzach</span>
                    <span class="inline-flex items-center gap-1">{{ $zawodnik->pzszach_ranking }}</span>
                </p>

                {{-- Ranking FIDE klasyczny --}}
                <p class="p-2 m-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span
                        class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.clock
                            class="text-main" /> Ranking FIDE &lpar;klasyczne&rpar;</span>
                    <span class="inline-flex items-center gap-1">{{ $zawodnik->fide_ranking_klasyczny }}</span>
                </p>

                {{-- Ranking FIDE szybkie --}}
                <p class="p-2 m-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span
                        class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.bolt
                            class="text-main" /> Ranking FIDE &lpar;szybkie&rpar;</span>
                    <span class="inline-flex items-center gap-1">{{ $zawodnik->fide_ranking_szybkie }}</span>
                </p>

                {{-- Ranking FIDE blyskawiczne --}}
                <p class="p-2 m-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span
                        class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.fire
                            class="text-main" /> Ranking FIDE &lpar;blyskawiczne&rpar;</span>
                    <span class="inline-flex items-center gap-1">{{ $zawodnik->fide_ranking_blyskawiczne }}</span>
                </p>

                {{-- PZSzach ID --}}
                <p class="p-2 m-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span
                        class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.paper-clip
                            class="text-main" /> Identyfikator PZSzach</span>
                    <span class="inline-flex items-center gap-1">{{ $zawodnik->pzszach_id == null ? 'Brak' : $zawodnik->pzszach_id }}</span>
                </p>

                {{-- FIDE ID --}}
                <p class="p-2 m-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span
                        class="inline-flex items-center gap-1 text-main uppercase text-sm font-black"><flux:icon.paper-clip
                            class="text-main" /> Identyfikator FIDE</span>
                    <span class="inline-flex items-center gap-1">{{ $zawodnik->fide_id == null ? 'Brak' : $zawodnik->fide_id }}</span>
                </p>
            </div>
        </div>
    </div>
</x-layouts::app>
