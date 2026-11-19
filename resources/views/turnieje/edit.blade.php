@php
    $currentRoute = Route::currentRouteName();
@endphp

{{-- zawartość --}}
<x-layouts::app :title="__('Edytuj turniej szachowy')">
    <div class="p-4 flex w-full flex-1 flex-col">
        <div class="grid w-full grid-cols-5">
            <a href="{{ route('turnieje.show', $turniej) }}" class="glass-off p-2">
                Komunikat
            </a>
            <a href="{{ route('zawodnicy.index', [$turniej]) }}" class="glass-off p-2">
                Wyniki
            </a>
            <a href="{{ route('rundy.show', $turniej) }}" class="glass-off p-2">
                Rundy
            </a>
            <a href="{{ route('turnieje.edit', $turniej) }}" class="glass p-2" style="border-bottom: none">
                Edytuj turniej
            </a>
            <a href="{{ route('turnieje.zgloszenia.index', $turniej) }}" class="glass-off p-2">
                Zgłoszenia
            </a>
        </div>

        <div class="p-4 flex w-full flex-1 flex-col glass" style="border-top: none;">

            <h1 class="m-2 p-2 text-2xl font-bold tracking-tight">Edytuj turniej</h1>

            <div class="rounded-lg w-full mx-auto relative">

                <form id="turniej-update-form" action="{{ route('turnieje.update', $turniej) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="m-2 rounded-lg border border-red-500/50 bg-red-500/10 p-3 text-red-200" role="alert">
                            <ul class="list-inside list-disc">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- ======================== Informacje ogólne ======================= --}}
                    <h2 class="m-2 p-2 text-l text-gray-300 tracking-tight">Informacje ogólne</h2>

                    <div class="grid grid-cols-2">
                        {{-- Nazwa --}}
                        <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                            <label for="nazwa"
                                class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                                <flux:icon.information-circle class="text-main" /> Nazwa turnieju*
                            </label>
                            <span class="inline-flex items-center">
                                <input value="{{ old('nazwa', $turniej->nazwa) }}" type="text" name="nazwa" id="nazwa"
                                    placeholder="Nazwa turnieju"
                                    class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20" required>
                            </span>
                        </p>
                        {{-- Miejsce --}}
                        <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                            <label for="miejsce"
                                class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                                <flux:icon.map class="text-main" /> Miejsce*
                            </label>
                            <span class="inline-flex items-center">
                                <input value="{{ old('miejsce', $turniej->miejsce) }}" type="text" name="miejsce" id="miejsce"
                                    placeholder="Miejsce"
                                    class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20" required>
                            </span>
                        </p>
                        {{-- Data rozpoczęcia --}}
                        <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                            <label for="data_rozpoczecia"
                                class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                                <flux:icon.calendar class="text-main" /> Data rozpoczęcia*
                            </label>
                            <span class="inline-flex items-center">
                                <input value="{{ old('data_rozpoczecia', $turniej->data_rozpoczecia) }}" type="date" name="data_rozpoczecia"
                                    id="data_rozpoczecia"
                                    class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20 " required>
                            </span>
                        </p>
                        {{-- Data zakończenia --}}
                        <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2">
                            <label for="data_zakonczenia"
                                class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                                <flux:icon.calendar class="text-main" /> Data zakończenia*
                            </label>
                            <span class="inline-flex items-center">
                                <input value="{{ old('data_zakonczenia', $turniej->data_zakonczenia) }}" type="date" name="data_zakonczenia"
                                    id="data_zakonczenia"
                                    class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20 " required>
                            </span>
                        </p>
                        {{-- Liczba rund --}}
                        <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                            <label for="liczba_rund"
                                class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                                <flux:icon.book-open class="text-main" /> Liczba rund*
                            </label>
                            <span class="inline-flex items-center">
                                <input value="{{ old('liczba_rund', $turniej->liczba_rund) }}" type="number" name="liczba_rund"
                                    id="liczba_rund" placeholder="Liczba rund"
                                    class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20 " required>
                            </span>
                        </p>
                        {{-- Limit zawodników --}}
                        <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                            <label for="limit_zawodnikow"
                                class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                                <flux:icon.user-group class="text-main" /> Limit zawodników*
                            </label>
                            <span class="inline-flex items-center">
                                <input value="{{ old('limit_zawodnikow', $turniej->limit_zawodnikow) }}" type="number" name="limit_zawodnikow"
                                    id="limit_zawodnikow" placeholder="Limit zawodników"
                                    class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20 " required>
                            </span>
                        </p>
                        {{-- Tempo gry --}}
                        <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                            <label for="tempo_gry"
                                class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                                <flux:icon.clock class="text-main" /> Tempo gry*
                            </label>
                            <span class="inline-flex items-center">
                                <input value="{{ old('tempo_gry', $turniej->tempo_gry) }}" type="text" name="tempo_gry" id="tempo_gry"
                                    placeholder="Tempo gry (np. 10'+5'')"
                                    class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20 " required>
                            </span>
                        </p>

                        {{-- Rodzaj szachów --}}
                        <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                            <label for="rodzaj_szachow"
                                class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                                <flux:icon.clock class="text-main" /> Rodzaj szachów*
                            </label>
                            <span class="inline-flex items-center">
                                <select name="rodzaj_szachow" id="rodzaj_szachow"
                                    class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20" required>
                                    @foreach (\App\Enums\RodzajSzachow::cases() as $rodzajSzachow)
                                        <option value="{{ $rodzajSzachow->value }}"
                                            @selected(old('rodzaj_szachow', $turniej->rodzaj_szachow->value) === $rodzajSzachow->value)
                                            class="bg-black/75">{{ $rodzajSzachow->label() }}</option>
                                    @endforeach
                                </select>
                            </span>
                        </p>

                        {{-- Status --}}
                        <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                            <label for="status_id"
                                class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                                <flux:icon.forward class="text-main" /> Status*
                            </label>
                            <span class="inline-flex items-center">
                                <select name="status_id" id="status_id"
                                    class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20 " required>
                                    @foreach ($turniej_statusy as $status)
                                        <option value="{{ $status->id }}"
                                                @selected((string) old('status_id', $turniej->status_id) === (string) $status->id)
                                                class="bg-black/75 ">
                                            {{ $status->nazwa }}
                                        </option>
                                    @endforeach
                                </select>
                            </span>
                        </p>
                        
                    </div>

                    {{-- ======================= Ograniczenia ========================== --}}
                    <h2 class="m-2 p-2 text-l text-gray-300 tracking-tight">Ograniczenia</h2>
                    <div class="grid grid-cols-2">
                        {{-- Ograniczenie do płci --}}
                        <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2">
                            <label for="limit_plec"
                                class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                                <flux:icon.question-mark-circle class="text-main" /> Ograniczenie do płci
                            </label>
                            <select name="limit_plec" id="limit_plec"
                                class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20">
                                <option value="" class="bg-black/75" @selected(blank(old('limit_plec', $turniej->limit_plec)))>Brak ograniczeń</option>
                                <option value="M" class="bg-black/75" @selected(old('limit_plec', $turniej->limit_plec) === 'M')>Mężczyźni</option>
                                <option value="K" class="bg-black/75" @selected(old('limit_plec', $turniej->limit_plec) === 'K')>Kobiety</option>
                            </select>
                        </p>

                        {{-- Ograniczenie do wieku --}}
                        <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2">
                            <label for="limit_wiek"
                                class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                                <flux:icon.question-mark-circle class="text-main" /> Limit wieku (lata)
                            </label>
                            <input value="{{ old('limit_wiek', $turniej->limit_wiek) }}" type="number" min="1"
                                name="limit_wiek" id="limit_wiek" placeholder="Brak ograniczeń"
                                class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20">
                        </p>

                        {{-- Ograniczenie do rankingu --}}
                        <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2">
                            <label for="limit_ranking_od"
                                class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                                <flux:icon.question-mark-circle class="text-main" /> Ranking od
                            </label>
                            <input value="{{ old('limit_ranking_od', $turniej->limit_ranking_od) }}" type="number" min="0"
                                name="limit_ranking_od" id="limit_ranking_od" placeholder="Brak dolnego limitu"
                                class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20">
                        </p>
                        <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2">
                            <label for="limit_ranking_do"
                                class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                                <flux:icon.question-mark-circle class="text-main" /> Ranking do
                            </label>
                            <input value="{{ old('limit_ranking_do', $turniej->limit_ranking_do) }}" type="number" min="0"
                                name="limit_ranking_do" id="limit_ranking_do" placeholder="Brak górnego limitu"
                                class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20">
                        </p>

                        {{-- Ograniczenie do klubu --}}
                        <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2">
                            <label for="limit_klub"
                                class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                                <flux:icon.question-mark-circle class="text-main" /> Turniej klubowy
                            </label>
                            <select name="limit_klub" id="limit_klub"
                                class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20" required>
                                <option value="1" class="bg-black/75" @selected((string) old('limit_klub', (int) $turniej->limit_klub) === '1')>Tak</option>
                                <option value="0" class="bg-black/75" @selected((string) old('limit_klub', (int) $turniej->limit_klub) === '0')>Nie</option>
                            </select>
                        </p>
                    </div>

                    {{-- =================== Informacje dodatkowe ======================= --}}
                    <h2 class="m-2 p-2 text-l text-gray-300 tracking-tight">Informacje dodatkowe</h2>
                    <div class="grid grid-cols-2">
                        <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                            <label for="zgloszony_do_pzszach"
                                class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                                <flux:icon.document-text class="text-main" /> Zgłoszony do PZSzach*
                            </label>
                            <span class="inline-flex items-center">
                                <select name="zgloszony_do_pzszach" id="zgloszony_do_pzszach"
                                    class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20 " required>
                                        <option value="0" @selected((string) old('zgloszony_do_pzszach', (int) $turniej->zgloszony_do_pzszach) === '0') class="bg-black/75">
                                            Nie
                                        </option>
                                        <option value="1" @selected((string) old('zgloszony_do_pzszach', (int) $turniej->zgloszony_do_pzszach) === '1') class="bg-black/75">
                                            Tak
                                        </option>
                                </select>
                            </span>
                        </p>

                        <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                            <label for="zgloszony_do_fide"
                                class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                                <flux:icon.document-text class="text-main" /> Zgłoszony do FIDE*
                            </label>
                            <span class="inline-flex items-center">
                                <select name="zgloszony_do_fide" id="zgloszony_do_fide"
                                    class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20 " required>
                                        <option value="0" @selected((string) old('zgloszony_do_fide', (int) $turniej->zgloszony_do_fide) === '0') class="bg-black/75">
                                            Nie
                                        </option>
                                        <option value="1" @selected((string) old('zgloszony_do_fide', (int) $turniej->zgloszony_do_fide) === '1') class="bg-black/75">
                                            Tak
                                        </option>
                                </select>
                            </span>
                        </p>
                    </div>
                    <div class="grid">
                        {{-- Opis --}}
                        <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                            <label for="opis"
                                class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                                <flux:icon.chat-bubble-bottom-center-text class="text-main" /> Opis
                            </label>
                            <span class="inline-flex items-center">
                                <textarea name="opis" id="opis" rows="4" placeholder="Opis turnieju"
                                    class="w-full px-4 py-2 border rounded-2xl bg-black/20 border-white/20 ">{{ old('opis', $turniej->opis) }}</textarea>
                            </span>
                        </p>
                        {{-- Komunikat --}}
                        <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                            <label for="komunikat"
                                class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                                <flux:icon.document class="text-main" /> Komunikat
                            </label>
                            <span class="inline-flex flex-col">
                                @if ($turniej->komunikat_path)
                                    <span class="mb-2 text-xs text-gray-400">
                                        Obecny plik:
                                        <a href="{{ Storage::url($turniej->komunikat_path) }}" target="_blank"
                                            rel="noopener noreferrer"
                                            class="underline text-blue-400 hover:text-blue-300">
                                            {{ basename($turniej->komunikat_path) }}
                                        </a>
                                    </span>
                                @endif
                                <input type="file" name="komunikat" id="komunikat"
                                    class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20 ">
                            </span>
                        </p>
                    </div>

                    <div class="grid grid-cols-4 gap-3 m-2 justify-end items-center whitespace-nowrap overflow-x-auto">

                        <p class="col-start-3">
                            <input type="button" value="Anuluj" onclick="window.history.back();"
                                class="w-full p-2 cursor-pointer bg-black/20 hover:bg-white/20 duration-200">
                        </p>
                        <p>
                            <input type="submit" value="Zaktualizuj turniej"
                                class="w-full p-2 cursor-pointer bg-main hover:bg-main-2 duration-200">
                        </p>

                    </div>
                </form>

                <div
                    class="w-[50%] absolute left-0 bottom-0 grid grid-cols-2 gap-3 m-2 justify-end items-center whitespace-nowrap overflow-x-auto">

                    <form action="{{ route('turnieje.destroy', $turniej) }}" method="POST"
                        enctype="multipart/form-data"
                        onsubmit="return confirm('Czy na pewno chcesz usunąć ten turniej?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full p-2 cursor-pointer bg-red-500 hover:bg-red-600 duration-200">
                            Usuń turniej
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-layouts::app>
