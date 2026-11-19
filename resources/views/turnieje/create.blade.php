<x-layouts::app :title="__('Utwórz turniej szachowy')">
    <div class="p-4 flex w-full flex-1 flex-col">
        <div class="p-4 flex w-full flex-1 flex-col glass">
            <h1 class="m-2 p-2 text-2xl font-bold tracking-tight">Utwórz turniej</h1>

            <form action="{{ route('turnieje.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
                    <div class="m-2 rounded-lg border border-red-500/50 bg-red-500/10 p-3 text-red-200" role="alert">
                        <ul class="list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <h2 class="m-2 p-2 text-l text-gray-300 tracking-tight">Informacje ogólne</h2>
                <div class="grid grid-cols-2">
                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2">
                        <label for="nazwa" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.information-circle class="text-main" /> Nazwa turnieju*
                        </label>
                        <input value="{{ old('nazwa') }}" type="text" name="nazwa" id="nazwa"
                            placeholder="Nazwa turnieju" maxlength="255"
                            class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20" required>
                    </p>

                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2">
                        <label for="miejsce" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.map class="text-main" /> Miejsce*
                        </label>
                        <input value="{{ old('miejsce') }}" type="text" name="miejsce" id="miejsce"
                            placeholder="Miejsce" maxlength="255"
                            class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20" required>
                    </p>

                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2">
                        <label for="data_rozpoczecia" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.calendar class="text-main" /> Data rozpoczęcia*
                        </label>
                        <input value="{{ old('data_rozpoczecia') }}" type="date" name="data_rozpoczecia"
                            id="data_rozpoczecia"
                            class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20" required>
                    </p>

                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2">
                        <label for="data_zakonczenia" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.calendar class="text-main" /> Data zakończenia*
                        </label>
                        <input value="{{ old('data_zakonczenia') }}" type="date" name="data_zakonczenia"
                            id="data_zakonczenia"
                            class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20" required>
                    </p>

                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2">
                        <label for="liczba_rund" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.book-open class="text-main" /> Liczba rund*
                        </label>
                        <input value="{{ old('liczba_rund') }}" type="number" min="1" name="liczba_rund"
                            id="liczba_rund" placeholder="Liczba rund"
                            class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20" required>
                    </p>

                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2">
                        <label for="limit_zawodnikow" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.user-group class="text-main" /> Limit zawodników*
                        </label>
                        <input value="{{ old('limit_zawodnikow') }}" type="number" min="1" name="limit_zawodnikow"
                            id="limit_zawodnikow" placeholder="Limit zawodników"
                            class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20" required>
                    </p>

                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2">
                        <label for="tempo_gry" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.clock class="text-main" /> Tempo gry*
                        </label>
                        <input value="{{ old('tempo_gry') }}" type="text" name="tempo_gry" id="tempo_gry"
                            placeholder="Tempo gry (np. 10'+5'')" maxlength="50"
                            class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20" required>
                    </p>

                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2">
                        <label for="rodzaj_szachow" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.clock class="text-main" /> Rodzaj szachów*
                        </label>
                        <select name="rodzaj_szachow" id="rodzaj_szachow"
                            class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20" required>
                            <option value="" class="bg-black/75" disabled @selected(blank(old('rodzaj_szachow')))>Wybierz rodzaj szachów</option>
                            @foreach (['Klasyczne', 'Szybkie', 'Błyskawiczne', 'Bullet'] as $rodzajSzachow)
                                <option value="{{ $rodzajSzachow }}"
                                    @selected(old('rodzaj_szachow') === $rodzajSzachow)
                                    class="bg-black/75">{{ $rodzajSzachow }}</option>
                            @endforeach
                        </select>
                    </p>

                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2">
                        <label for="status_id" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.forward class="text-main" /> Status*
                        </label>
                        <select name="status_id" id="status_id"
                            class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20" required>
                            <option value="" class="bg-black/75" disabled @selected(blank(old('status_id')))>Wybierz status</option>
                            @foreach ($turniej_statusy as $status)
                                <option value="{{ $status->id }}"
                                    @selected((string) old('status_id') === (string) $status->id)
                                    class="bg-black/75">{{ $status->nazwa }}</option>
                            @endforeach
                        </select>
                    </p>
                </div>

                <h2 class="m-2 p-2 text-l text-gray-300 tracking-tight">Ograniczenia</h2>
                <div class="grid grid-cols-2">
                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2">
                        <label for="limit_plec" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.question-mark-circle class="text-main" /> Ograniczenie do płci
                        </label>
                        <select name="limit_plec" id="limit_plec"
                            class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20">
                            <option value="" class="bg-black/75" @selected(blank(old('limit_plec')))>Brak ograniczeń</option>
                            <option value="M" class="bg-black/75" @selected(old('limit_plec') === 'M')>Mężczyźni</option>
                            <option value="K" class="bg-black/75" @selected(old('limit_plec') === 'K')>Kobiety</option>
                        </select>
                    </p>

                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2">
                        <label for="limit_wiek" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.question-mark-circle class="text-main" /> Limit wieku (lata)
                        </label>
                        <input value="{{ old('limit_wiek') }}" type="number" min="1" name="limit_wiek"
                            id="limit_wiek" placeholder="Brak ograniczeń"
                            class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20">
                    </p>

                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2">
                        <label for="limit_ranking_od" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.question-mark-circle class="text-main" /> Ranking od
                        </label>
                        <input value="{{ old('limit_ranking_od') }}" type="number" min="0" name="limit_ranking_od"
                            id="limit_ranking_od" placeholder="Brak dolnego limitu"
                            class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20">
                    </p>

                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2">
                        <label for="limit_ranking_do" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.question-mark-circle class="text-main" /> Ranking do
                        </label>
                        <input value="{{ old('limit_ranking_do') }}" type="number" min="0" name="limit_ranking_do"
                            id="limit_ranking_do" placeholder="Brak górnego limitu"
                            class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20">
                    </p>

                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2">
                        <label for="limit_klub" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.question-mark-circle class="text-main" /> Turniej klubowy
                        </label>
                        <select name="limit_klub" id="limit_klub"
                            class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20" required>
                            <option value="1" class="bg-black/75"
                                @selected((string) old('limit_klub', '1') === '1')>Tak</option>
                            <option value="0" class="bg-black/75"
                                @selected((string) old('limit_klub', '1') === '0')>Nie</option>
                        </select>
                    </p>
                </div>

                <h2 class="m-2 p-2 text-l text-gray-300 tracking-tight">Informacje dodatkowe</h2>
                <div class="grid grid-cols-2">
                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2">
                        <label for="zgloszony_do_pzszach"
                            class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.document-text class="text-main" /> Zgłoszony do PZSzach*
                        </label>
                        <select name="zgloszony_do_pzszach" id="zgloszony_do_pzszach"
                            class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20" required>
                            <option value="0" class="bg-black/75"
                                @selected((string) old('zgloszony_do_pzszach', '0') === '0')>Nie</option>
                            <option value="1" class="bg-black/75"
                                @selected((string) old('zgloszony_do_pzszach', '0') === '1')>Tak</option>
                        </select>
                    </p>

                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2">
                        <label for="zgloszony_do_fide"
                            class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.document-text class="text-main" /> Zgłoszony do FIDE*
                        </label>
                        <select name="zgloszony_do_fide" id="zgloszony_do_fide"
                            class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20" required>
                            <option value="0" class="bg-black/75"
                                @selected((string) old('zgloszony_do_fide', '0') === '0')>Nie</option>
                            <option value="1" class="bg-black/75"
                                @selected((string) old('zgloszony_do_fide', '0') === '1')>Tak</option>
                        </select>
                    </p>

                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 col-span-2">
                        <label for="opis" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.chat-bubble-bottom-center-text class="text-main" /> Opis
                        </label>
                        <textarea name="opis" id="opis" rows="4" placeholder="Opis turnieju"
                            class="w-full px-4 py-2 border rounded-2xl bg-black/20 border-white/20">{{ old('opis') }}</textarea>
                    </p>

                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 col-span-2">
                        <label for="komunikat" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.document class="text-main" /> Komunikat
                        </label>
                        <input type="file" name="komunikat" id="komunikat" accept=".pdf,.doc,.docx"
                            class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20">
                    </p>
                </div>

                <div class="grid grid-cols-4 gap-3 m-2 justify-end items-center">
                    <p class="col-start-3">
                        <input type="button" value="Anuluj" onclick="window.history.back();"
                            class="w-full p-2 cursor-pointer bg-black/20 hover:bg-white/20 duration-200">
                    </p>
                    <p>
                        <input type="submit" value="Utwórz turniej"
                            class="w-full p-2 cursor-pointer bg-main hover:bg-main-2 duration-200">
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
