{{-- @include('partials.turniej-nav') --}}
<x-layouts::app :title="__('Rundy')">
    <div class="p-4 flex w-full flex-1 flex-col">

        <div class="grid w-full grid-cols-5">
            <a href="{{ route('turnieje.show', $turniej) }}" class="glass-off p-2">
                Komunikat
            </a>
            <a href="{{ route('zawodnicy.index', [$turniej]) }}" class="glass-off p-2">
                Wyniki
            </a>
            <a href="{{ route('rundy.show', [$turniej]) }}" class="glass p-2" style="border-bottom: none">
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
                {{-- @if (session('error'))
                    <p class="p-4 text-red-300" role="alert">{{ session('error') }}</p>
                @endif
                @if (session('success'))
                    <p class="p-4 text-green-300" role="status">{{ session('success') }}</p>
                @endif
                <p class="px-4 text-gray-300">Status rundy: {{ $runda->status->label() }}</p> --}}
                @if ($runda->status === \App\Enums\RundaStatus::NieRozpoczeta)
                    <p class="p-4 text-gray-300">Runda jeszcze nie rozpoczęta.</p>
                    <form action="{{ route('rundy.store', [$turniej, $runda]) }}" method="POST">
                        @csrf
                        <flux:button type="submit" class="mt-2 w-fit shrink-0 hover:cursor-pointer"
                            icon="">
                            Wygeneruj listę par zawodników
                        </flux:button>
                    </form>
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
                                    @if ($runda->status === \App\Enums\RundaStatus::Trwajaca)
                                    <th class="border-b border-gray-200 p-2 text-sm text-gray-300"></th>
                                    @endif
                                    <th class="border-b border-gray-200 p-2 text-sm text-gray-300 text-left">Czarne</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($partie as $partia)
                                    <tr>
                                        <td class="border-b border-gray-200 p-2 text-sm text-white">{{ $partia->stolik }}</td>
                                        <td class="border-b border-gray-200 p-2 text-sm text-white text-right">{{ $partia->bialeZawodnik->name }}</td>
                                        @if ($runda->status === \App\Enums\RundaStatus::Trwajaca)
                                            <td class="border-b border-gray-200 p-2 text-sm text-white text-center">
                                                <input type="number" value="{{ $partia->biale_wynik_punktowy | 0 }}" class="bg-black/20 text-center"/>
                                                {{ ' - ' }}
                                                <input type="number" value="{{ $partia->czarne_wynik_punktowy | 0 }}" class="bg-black/20 text-center"/>
                                            </td>
                                        @endif
                                        <td class="border-b border-gray-200 p-2 text-sm text-white text-left">{{ $partia->czarneZawodnik->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($runda->status === \App\Enums\RundaStatus::Przygotowana)
                            <form action="{{ route('rundy.begin', [$turniej, $runda]) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <flux:button type="submit" class="mt-2 w-fit shrink-0 hover:cursor-pointer"
                                    icon="">
                                    Rozpocznij rundę
                                </flux:button>
                            </form>
                        @elseif ($runda->status === \App\Enums\RundaStatus::Trwajaca)
                            <form action="{{ route('rundy.store', [$turniej, $runda]) }}" method="POST">
                                @csrf
                                <flux:button type="submit" class="mt-2 w-fit shrink-0 hover:cursor-pointer"
                                    icon="">
                                    Zatwierdź wyniki i zakończ rundę
                                </flux:button>
                            </form>
                        @endif
                    @endif
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
