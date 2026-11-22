{{-- @include('partials.turniej-nav') --}}
<x-layouts::app :title="__('Wyniki')">
    <div class="p-4 flex w-full flex-1 flex-col">

        <nav class="flex w-full flex-nowrap overflow-x-auto">
            <a href="{{ route('turnieje.show', $turniej) }}" class="glass-off grow whitespace-nowrap p-2">
                Komunikat
            </a>
            <a href="{{ route('zawodnicy.index', [$turniej]) }}" class="glass grow whitespace-nowrap p-2" style="border-bottom: none">
                Wyniki
            </a>
            <a href="{{ route('rundy.show', [$turniej]) }}" class="glass-off grow whitespace-nowrap p-2">
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
                @if ($numerPoprzedniejRundy !== null)
                    <a href="{{ route('zawodnicy.index', [$turniej, 'runda' => $numerPoprzedniejRundy]) }}"
                        aria-label="Poprzednia runda" class="cursor-pointer">
                        <flux:icon.chevron-left />
                    </a>
                @else
                    <button type="button" disabled aria-label="Brak poprzedniej rundy"
                        class="cursor-not-allowed text-gray-500">
                        <flux:icon.chevron-left />
                    </button>
                @endif
                <h1 class="m-2 p-2 text-2xl font-bold tracking-tight">
                    {{ $listaStartowa ? 'Lista startowa' : 'Wyniki po rundzie '.$wybranaRunda->numer }}
                </h1>
                @if ($numerNastepnejRundy !== null)
                    <a href="{{ route('zawodnicy.index', [$turniej, 'runda' => $numerNastepnejRundy]) }}"
                        aria-label="Następna runda" class="cursor-pointer">
                        <flux:icon.chevron-right />
                    </a>
                @else
                    <button type="button" disabled aria-label="Brak następnej rundy"
                        class="cursor-not-allowed text-gray-500">
                        <flux:icon.chevron-right />
                    </button>
                @endif
            </div>

            <form action="{{ route('zawodnicy.index', $turniej) }}" method="GET">
                <div class="px-4 flex items-center gap-4">
                    <input type="hidden" name="runda" value="{{ $numerRundy }}">
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
                @if ($listaStartowa && $turniejZawodnik->isEmpty())
                    <p class="p-4 text-gray-300">Jeszcze nie ma zgłoszonych zawodników</p>
                @elseif (!$listaStartowa && $wybranaRunda->status !== \App\Enums\RundaStatus::Zakonczona)
                    <p class="p-4 text-gray-300">Jeszcze nie ma wyników dla tej rundy</p>
                @elseif ($turniejZawodnik->isEmpty())
                    <p class="p-4 text-gray-300">
                        Jeszcze nie ma wyników dla tej rundy
                    </p>
                @else
                <div class="m-4 glass">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-main">
                                <th class="border-b border-gray-200/20 p-2 text-sm text-gray-300">Miejsce</th>
                                <th class="border-b border-gray-200/20 p-2 text-sm text-gray-300">Zawodnik</th>
                                <th class="border-b border-gray-200/20 p-2 text-sm text-gray-300">Ranking PZSzach</th>
                                <th class="border-b border-gray-200/20 p-2 text-sm text-gray-300">{{--Tytul--}}</th>
                                @unless ($listaStartowa)
                                    <th class="border-b border-gray-200/20 p-2 text-sm text-gray-300">Punkty</th>
                                @endunless
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $miejsce = 1;
                            @endphp
                            @foreach ($turniejZawodnik as $zawodnik)
                                <tr class="[&:last-child>*]:border-b-0"
                                    class="hover:bg-gray-700 {{ request('nazwa') && str_contains(strtolower($zawodnik->zawodnik->name), strtolower(request('nazwa'))) ? 'bg-gray-500' : '' }}">
                                    <td class="border-b border-gray-200/20 p-2 text-sm text-white">{{ $miejsce++ }}</td>
                                    <td class="border-b border-gray-200/20 p-2 text-sm text-white">
                                        <a href="{{ route('zawodnicy.show', $zawodnik->zawodnik) }}">
                                            {{ $zawodnik->zawodnik->name }}
                                        </a>
                                    </td>
                                    <td class="border-b border-gray-200/20 p-2 text-sm text-white">
                                        {{ $zawodnik->zawodnik->pzszach_ranking }}</td>
                                    <td class="border-b border-gray-200/20 p-2 text-sm text-white">
                                        {{ $zawodnik->zawodnik->tytul }}</td>
                                    @unless ($listaStartowa) {{-- if(false) --}}
                                        <td class="border-b border-gray-200/20 p-2 text-sm text-white">
                                            {{ floor($zawodnik->punkty_historia) }}
                                            {{ $zawodnik->punkty_historia != floor($zawodnik->punkty_historia) ? '½' : '' }}
                                        </td>
                                    @endunless
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
