<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">{{ __('Turnieje') }}</h1>
            
        </div>
        <div>
            @foreach ($turnieje as $turniej)
                <div class="p-4 m-4 rounded-lg border border-gray-200 hover:bg-gray-900 hover:cursor-pointer transition-colors">
                    <h2 class="p-1 text-xl font-bold">{{ $turniej->nazwa }}</h2>
                    <p class="p-1 text-white flex items-center gap-3 whitespace-nowrap overflow-x-auto">
                        <span class="inline-flex items-center gap-1"><flux:icon.calendar /> {{ $turniej->data_rozpoczecia }} - {{ $turniej->data_zakonczenia }}</span>
                        <span class="inline-flex items-center gap-1"><flux:icon.map /> {{ $turniej->miejsce }}</span>
                    </p>
                    <p class="p-1 text-white flex items-center gap-3 whitespace-nowrap overflow-x-auto">
                        <span class="inline-flex items-center gap-1"><flux:icon.book-open /> {{ $turniej->liczba_rund }} rund</span>
                        <span class="inline-flex items-center gap-1"><flux:icon.user /> {{ $turniej->liczba_zawodnikow }} / {{ $turniej->limit_zawodnikow }} zawodników</span>
                        <span class="inline-flex items-center gap-1"><flux:icon.clock /> {{ $turniej->tempo_gry }}</span>
                    </p>
                
                </div>  
                
            @endforeach
        </div>
    </div>
</x-layouts::app>
