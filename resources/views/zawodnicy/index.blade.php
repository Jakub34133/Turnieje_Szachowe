{{-- @include('partials.turniej-nav') --}}
<x-layouts::app :title="__('Wyniki')">
    <div class="p-4 flex w-full flex-1 flex-col">

        <div class="grid w-full grid-cols-5">
            <a href="{{ route('turnieje.show', $turniej) }}" class="glass-off p-2">
                Komunikat
            </a>
            <a href="{{ route('zawodnicy.index', [$turniej]) }}" class="glass p-2" style="border-bottom: none">
                Wyniki
            </a>
            <a href="#" class="glass-off p-2">
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

            <h1 class="m-2 p-2 text-2xl font-bold tracking-tight">Wyniki</h1>

            <form action="{{ route('zawodnicy.index', $turniej) }}" method="GET">
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
                @if ($turniejZawodnik->isEmpty())
                    <p class="p-4 text-gray-300">Nie ma jeszcze żadnych zawodników.</p>
                @else
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="border-b border-gray-200 p-2 text-sm text-gray-300">Miejsce</th>
                                <th class="border-b border-gray-200 p-2 text-sm text-gray-300">Zawodnik</th>
                                <th class="border-b border-gray-200 p-2 text-sm text-gray-300">Ranking PZSzach</th>
                                <th class="border-b border-gray-200 p-2 text-sm text-gray-300">Tytuł</th>
                                <th class="border-b border-gray-200 p-2 text-sm text-gray-300">Punkty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $miejsce = 1;
                            @endphp
                            @foreach ($turniejZawodnik as $zawodnik)
                                <tr
                                    class="hover:bg-gray-700 {{ request('nazwa') && str_contains(strtolower($zawodnik->zawodnik->name), strtolower(request('nazwa'))) ? 'bg-gray-500' : '' }}">
                                    <td class="border-b border-gray-200 p-2 text-sm text-white">{{ $miejsce++ }}</td>
                                    <td class="border-b border-gray-200 p-2 text-sm text-white">
                                        {{ $zawodnik->zawodnik->name }}</td>
                                    <td class="border-b border-gray-200 p-2 text-sm text-white">
                                        {{ $zawodnik->zawodnik->pzszach_ranking }}</td>
                                    <td class="border-b border-gray-200 p-2 text-sm text-white">
                                        {{ $zawodnik->zawodnik->tytul }}</td>
                                    <td class="border-b border-gray-200 p-2 text-sm text-white">{{ $zawodnik->punkty }}
                                    </td>
                                </tr>
                            @endforeach
                @endif
                </tbody>
                </table>
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
