<x-layouts::app :title="__('Turnieje')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="px-4 flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight">{{ __('Turnieje szachowe') }}</h1>
            <flux:button href="{{ route('turnieje.create') }}" class="mt-2" icon="plus">
                {{ __('Utwórz turniej') }}
            </flux:button>
        </div>
        <form action="{{ route('turnieje.index') }}" method="GET">
            <div class="px-4 flex items-center gap-4">
                <p>
                    <label for="nazwa" class="block mb-2 text-sm font-medium text-gray-300">Nazwa turnieju</label>
                    <input type="text" id="nazwa" name="nazwa" value="{{ request('nazwa') }}" placeholder="Nazwa turnieju" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white">
                </p>
                <p>
                    <label for="status_id" class="block mb-2 text-sm font-medium text-gray-300">Status</label>
                    <select name="status_id" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white">
                        <option value="">Wszystkie</option>
                        @foreach($turniej_statusy as $status)
                            <option value="{{ $status->id }}" @selected(request('status_id') == $status->id)>
                                {{ $status->nazwa }}
                            </option>
                        @endforeach
                    </select>
                </p>
                <flux:button type="submit" class="mt-2 w-fit shrink-0 hover:cursor-pointer" icon="magnifying-glass">
                    Szukaj
                </flux:button>
            </div>
        </form>
        <div>
            @foreach ($turnieje as $turniej)
                
                <a href="{{ route('turnieje.show', $turniej) }}"
                   class="block p-4 m-4 rounded-lg border border-gray-200 hover:bg-gray-900 hover:cursor-pointer transition-colors">
                    <h2 class="p-1 text-xl font-bold">{{ $turniej->nazwa }}</h2>
                    <p class="p-1 text-white flex items-center gap-3 whitespace-nowrap overflow-x-auto">
                        <span class="inline-flex items-center gap-1"><flux:icon.calendar class="text-main"/> {{ $turniej->data_rozpoczecia }} - {{ $turniej->data_zakonczenia }}</span>
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
</x-layouts::app>
