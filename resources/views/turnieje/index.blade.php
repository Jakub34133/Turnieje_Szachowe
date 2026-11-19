<x-layouts::app :title="__('Turnieje')">
    <div class="p-4 flex w-full flex-1 flex-col">
        <div class="p-4 flex w-full flex-1 flex-col glass">

            <div class="flex justify-between">
                <h1 class="m-2 p-2 text-2xl font-bold tracking-tight">Turnieje Szachowe</h1>
                <flux:button href="{{ route('turnieje.create') }}" class="mt-2" icon="plus">
                    {{ __('Utwórz turniej') }}
                </flux:button>
            </div>

            <form action="{{ route('turnieje.index') }}" method="GET">
                <div class="px-4 flex items-center gap-4">
                    <p>
                        <label for="nazwa" class="block mb-2 text-sm font-medium text-gray-300">Nazwa
                            turnieju</label>
                        <input type="text" id="nazwa" name="nazwa" value="{{ request('nazwa') }}"
                            placeholder="Nazwa turnieju"
                            class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white">
                    </p>
                    <p>
                        <label for="status_id" class="block mb-2 text-sm font-medium text-gray-300">Status</label>
                        <select name="status_id" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white">
                            <option value="">Wszystkie</option>
                            @foreach ($turniej_statusy as $status)
                                <option value="{{ $status->id }}" @selected(request('status_id') == $status->id)>
                                    {{ $status->nazwa }}
                                </option>
                            @endforeach
                        </select>
                    </p>
                    <flux:button type="submit" class="mt-2 w-fit shrink-0 hover:cursor-pointer"
                        icon="magnifying-glass">
                        Szukaj
                    </flux:button>
                </div>
            </form>
            
            <div>
                @if (empty($turnieje))
                    <h2 class="m-2 p-2 pt-0 mt-0 text-l text-gray-300 tracking-tight">Brak turniejów do wyświetlenia
                    </h2>
                @else
                    @foreach ($turnieje as $turniej)
                        <a href="{{ route('turnieje.show', $turniej) }}"
                            class="p-2 m-4 grid items-center whitespace-nowrap overflow-x-auto glass-2 hover:bg-main/10">
                            <h2 class="p-1 text-xl font-bold">{{ $turniej->nazwa }}</h2>
                            <p class="p-1 text-white flex items-center gap-3 whitespace-nowrap overflow-x-auto">
                                <span class="inline-flex items-center gap-1">
                                    <flux:icon.calendar class="text-main" /> {{ $turniej->data_rozpoczecia }} -
                                    {{ $turniej->data_zakonczenia }}
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <flux:icon.map class="text-main" /> {{ $turniej->miejsce }}
                                </span>
                            </p>
                            <p class="p-1 text-white flex items-center gap-3 whitespace-nowrap overflow-x-auto">
                                <span class="inline-flex items-center gap-1">
                                    <flux:icon.book-open class="text-main" /> {{ $turniej->liczba_rund }} rund</span>
                                <span class="inline-flex items-center gap-1">
                                    <flux:icon.user-group class="text-main" /> {{ $turniej->liczba_zawodnikow }} /
                                    {{ $turniej->limit_zawodnikow }} zawodników</span>
                                <span class="inline-flex items-center gap-1">
                                    <flux:icon.clock class="text-main" /> {{ $turniej->rodzaj_szachow->label() }}
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <flux:icon.forward class="text-main" /> {{ $turniej->status->nazwa }}
                                </span>
                            </p>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</x-layouts::app>
