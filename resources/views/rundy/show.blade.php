{{-- @include('partials.turniej-nav') --}}
<x-layouts::app :title="__('Rundy')">
    @if (session('error'))
        <p class="p-2 m-4 flex items-center justify-between whitespace-nowrap overflow-x-auto glass-2" role="alert">
            {{ session('error') }}
        </p>
    @endif
    @if (session('success'))
        <p class="p-2 m-4 flex items-center justify-between whitespace-nowrap overflow-x-auto glass-2" role="status">
            {{ session('success') }}
        </p>
    @endif
    <div class="p-4 flex w-full flex-1 flex-col">
        <nav class="flex w-full flex-nowrap overflow-x-auto">
            <a href="{{ route('turnieje.show', $turniej) }}" class="glass-off grow whitespace-nowrap p-2">
                Komunikat
            </a>
            <a href="{{ route('zawodnicy.index', [$turniej]) }}" class="glass-off grow whitespace-nowrap p-2">
                Wyniki
            </a>
            <a href="{{ route('rundy.show', [$turniej]) }}" class="glass grow whitespace-nowrap p-2" style="border-bottom: none">
                Rundy
            </a>
            @can('update', $turniej)
                <a href="{{ route('turnieje.edit', $turniej) }}" class="glass-off grow whitespace-nowrap p-2">
                    Edytuj turniej
                </a>
            @endcan
            @can('viewAny', [\App\Models\Zgloszenie::class, $turniej])
                <a href="{{ route('turnieje.zgloszenia.index', $turniej) }}" class="glass-off grow whitespace-nowrap p-2">
                    Zgłoszenia
                </a>
            @endcan
        </nav>
        <div class="p-4 flex w-full flex-1 flex-col glass" style="border-top: none;">

            <div class="flex gap-2 items-center">
                @if ($poprzedniaRunda)
                    <a href="{{ route('rundy.show', [$turniej, $poprzedniaRunda]) }}" aria-label="Poprzednia runda"
                        class="cursor-pointer">
                        <flux:icon.chevron-left />
                    </a>
                @else
                    <button type="button" disabled aria-label="Brak poprzedniej rundy"
                        class="cursor-not-allowed text-gray-500">
                        <flux:icon.chevron-left />
                    </button>
                @endif
                <h1 class="m-2 p-2 text-2xl font-bold tracking-tight">Runda {{ $runda->numer }}</h1>
                @if ($nastepnaRunda)
                    <a href="{{ route('rundy.show', [$turniej, $nastepnaRunda]) }}" aria-label="Następna runda"
                        class="cursor-pointer">
                        <flux:icon.chevron-right />
                    </a>
                @else
                    <button type="button" disabled aria-label="Brak następnej rundy"
                        class="cursor-not-allowed text-gray-500">
                        <flux:icon.chevron-right />
                    </button>
                @endif
            </div>

            <form action="{{ route('rundy.show', [$turniej, $runda]) }}" method="GET">
                <div class="px-4 flex items-center gap-4">
                    <p>
                        <label for="nazwa" class="block mb-2 text-sm font-medium text-gray-300">Zawodnik</label>
                        <input type="text" id="nazwa" name="nazwa" value="{{ request('nazwa') }}"
                            placeholder="Nazwa zawodnika"
                            class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white">
                    </p>
                    <flux:button type="submit" class="mt-2 w-fit shrink-0 hover:cursor-pointer"
                        icon="magnifying-glass">
                        Szukaj
                    </flux:button>
                </div>
            </form>

            <div>

                <p class="px-4 text-gray-300">Status rundy: {{ $runda->status->label() }}</p>
                @if ($runda->status === \App\Enums\RundaStatus::NieRozpoczeta)
                    @can('update', [$runda, $turniej])
                        @if ($moznaWygenerowacPary)
                            <form action="{{ route('rundy.store', [$turniej, $runda]) }}" method="POST">
                                @csrf
                                <flux:button type="submit" class="mt-2 w-fit shrink-0 hover:cursor-pointer" icon="">
                                    Wygeneruj listę par zawodników
                                </flux:button>
                            </form>
                        @else
                            <p class="p-4 text-gray-300">Poprzednia runda musi zostać zakończona przed wygenerowaniem par.
                            </p>
                        @endif
                    @else
                        <p class="p-4 text-gray-300">Pary tej rundy nie zostały jeszcze wygenerowane.</p>
                    @endcan
                @else
                    <h2 class="px-4 pt-4 text-lg font-semibold">Pary rundy</h2>
                    @if ($partie->isEmpty())
                        <p class="p-4 text-gray-300">W tej rundzie nie ma partii.</p>
                    @else
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="border-b border-gray-200 p-2 text-sm text-gray-300">Stolik</th>
                                    <th class="border-b border-gray-200 p-2 text-sm text-gray-300 text-right">Białe</th>
                                    <th class="border-b border-gray-200 p-2 text-sm text-gray-300"></th>
                                    <th class="border-b border-gray-200 p-2 text-sm text-gray-300 text-left">Czarne</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($partie as $partia)
                                    <tr>
                                        <td class="border-b border-gray-200 p-2 text-sm text-white">
                                            {{ $partia->stolik }}</td>
                                        <td class="border-b border-gray-200 p-2 text-sm text-white text-right">
                                            <a href="{{ route('zawodnicy.show', [$partia->bialeZawodnik]) }}">
                                                {{ $partia->bialeZawodnik->name }}
                                            </a>
                                        </td>
                                        <td class="border-b border-gray-200 p-2 text-sm text-white text-center">
                                            @if ($runda->status === \App\Enums\RundaStatus::Trwajaca)
                                                @can('update', [$runda, $turniej])
                                                    <form
                                                        action="{{ route('partie.update', [$turniej, $runda, $partia]) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <select name="biale_wynik_punktowy" aria-label="Wynik białych"
                                                            onchange="this.form.submit()" class="bg-black/20 text-center">
                                                            <option value=""
                                                                {{ $partia->biale_wynik_punktowy === null ? 'selected' : '' }}
                                                                class="bg-black">—</option>
                                                            @foreach (\App\Enums\WynikPunktowyPartii::cases() as $wynik)
                                                                <option value="{{ $wynik->value }}"
                                                                    {{ $partia->biale_wynik_punktowy === $wynik ? 'selected' : '' }}
                                                                    class="bg-black">{{ $wynik->label() }}</option>
                                                            @endforeach
                                                        </select>
                                                    </form>
                                                    {{ ' - ' }}
                                                    <form
                                                        action="{{ route('partie.update', [$turniej, $runda, $partia]) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <select name="czarne_wynik_punktowy" aria-label="Wynik czarnych"
                                                            onchange="this.form.submit()" class="bg-black/20 text-center">
                                                            <option value=""
                                                                {{ $partia->czarne_wynik_punktowy === null ? 'selected' : '' }}
                                                                class="bg-black">—</option>
                                                            @foreach (\App\Enums\WynikPunktowyPartii::cases() as $wynik)
                                                                <option value="{{ $wynik->value }}"
                                                                    {{ $partia->czarne_wynik_punktowy === $wynik ? 'selected' : '' }}
                                                                    class="bg-black">{{ $wynik->label() }}</option>
                                                            @endforeach
                                                        </select>
                                                    </form>
                                                @else
                                                    {{ $partia->biale_wynik_punktowy?->label() ?? '—' }}
                                                    {{ ' - ' }}
                                                    {{ $partia->czarne_wynik_punktowy?->label() ?? '—' }}
                                                @endcan
                                            @elseif ($runda->status === \App\Enums\RundaStatus::Zakonczona)
                                                {{ $partia->biale_wynik_punktowy?->label() ?? '—' }}
                                                {{ ' - ' }}
                                                {{ $partia->czarne_wynik_punktowy?->label() ?? '—' }}
                                            @endif
                                        </td>
                                        <td class="border-b border-gray-200 p-2 text-sm text-white text-left">
                                            <a href="{{ route('zawodnicy.show', [$partia->czarneZawodnik]) }}">
                                                {{ $partia->czarneZawodnik->name }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    @if ($runda->wolnyLosZawodnik)
                        <p class="px-4 pt-3 text-gray-300">
                            Wolny los:
                            <a href="{{ route('zawodnicy.show', [$runda->wolnyLosZawodnik]) }}" class="underline">
                                {{ $runda->wolnyLosZawodnik->name }}
                            </a>
                        </p>
                    @endif

                    @can('update', [$runda, $turniej])
                        @if ($partie->isNotEmpty())
                            @if ($runda->status === \App\Enums\RundaStatus::Przygotowana)
                                <form action="{{ route('rundy.begin', [$turniej, $runda]) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <flux:button type="submit" class="mt-2 w-fit shrink-0 hover:cursor-pointer"
                                        icon="">
                                        Rozpocznij rundę
                                    </flux:button>
                                </form>
                            @elseif ($runda->status === \App\Enums\RundaStatus::Trwajaca)
                                <form action="{{ route('rundy.finish', [$turniej, $runda]) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <flux:button type="submit" class="mt-2 w-fit shrink-0 hover:cursor-pointer"
                                        icon="">
                                        Zatwierdź wyniki i zakończ rundę
                                    </flux:button>
                                </form>
                            @endif
                        @endif
                    @endcan
                @endif
            </div>
        </div>
    </div>
</x-layouts::app>

{{-- <span class="inline-flex items-center gap-1"><flux:icon.calendar class="text-main"/> {{ $turniej->data_rozpoczecia }} - {{ $turniej->data_zakonczenia }}</span>
                        <span class="inline-flex items-center gap-1"><flux:icon.map class="text-main"/> {{ $turniej->miejsce }}</span>
                    </p>
                    <p class="p-1 text-white flex items-center gap-3 whitespace-nowrap overflow-x-auto">
                        <span class="inline-flex items-center gap-1"><flux:icon.book-open class="text-main"/> {{ $turniej->liczba_rund }} rund</span>
                        <span class="inline-flex items-center gap-1"><flux:icon.user-group class="text-main"/> {{ $turniej->liczba_zawodnikow }} / {{ $turniej->limit_zawodnikow }} zawodników</span>
                        <span class="inline-flex items-center gap-1"><flux:icon.clock class="text-main"/> {{ $turniej->tempo_gry }}</span>
                        <span class="inline-flex items-center gap-1"><flux:icon.forward class="text-main"/> {{ $turniej->status->nazwa }}</span>
                    </p>
                </a>  
                
            @endforeach
        </div>
    </div>
</x-layouts::app> --}}
