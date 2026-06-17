@include('partials.turniej-nav')

<x-layouts::app :title="__('Zgłoszenia')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        @if (session('error'))
            <div class="mx-4 rounded-lg border border-red-500 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="mx-4 rounded-lg border border-emerald-500 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="px-4 flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight">{{ __('Zgłoszenia') }}</h1>
        </div>

        

        <form action="{{ route('turnieje.zgloszenia.index', $turniej) }}" method="GET">
            <div class="px-4 flex items-center gap-4">
                <p>
                    <label for="nazwa" class="block mb-2 text-sm font-medium text-gray-300">Zawodnik</label>
                    <input type="text" id="nazwa" name="nazwa" value="{{ request('nazwa') }}" placeholder="Nazwa zawodnika" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white">
                </p>
                <p>
                    <label for="status_id" class="block mb-2 text-sm font-medium text-gray-300">Status</label>
                    <select name="status_id" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white">
                        <option value="">Wszystkie</option>
                        @foreach($zgloszenia_statusy as $status)
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
            @foreach ($zgloszenia as $zgloszenie)
                <div class="block p-2 m-2 flex justify-between rounded-lg border border-gray-200 hover:bg-gray-900 hover:cursor-pointer transition-colors">
                    <div class="p-2">
                        <p class="text-white flex items-center gap-3 whitespace-nowrap overflow-x-auto">
                            <span class="inline-flex items-center gap-1"><flux:icon.user class="text-main"/> {{ $zgloszenie->zawodnik->name }}</span>
                        </p>
                        <p class="pt-1 text-gray-300 flex items-center gap-3 overflow-x-auto">
                            <span class="inline-flex items-center gap-1"> {{ $zgloszenie->komentarz }}</span>
                        </p>
                    </div>
                    <div class="flex gap-2 pr-2 items-center">
                        @if($zgloszenie->status->nazwa === 'wysłane')
                        <form action="{{ route('turnieje.zgloszenia.approve', [$turniej, $zgloszenie]) }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Czy na pewno chcesz zatwierdzić to zgłoszenie?');">
                            @csrf
                            @method('POST')
                            <flux:button type="submit" class="bg-main" icon="check">
                            </flux:button>
                        </form>
                        <form action="{{ route('turnieje.zgloszenia.reject', [$turniej, $zgloszenie]) }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Czy na pewno chcesz odrzucić to zgłoszenie?');">
                            @csrf
                            @method('POST')
                            <flux:button type="submit" class="bg-red-500" icon="x-mark">
                            </flux:button>
                        </form>
                        @else
                            @if($zgloszenie->status->nazwa === 'zatwierdzone')
                                <span class="inline-flex items-center gap-1 bg-main text-white px-2 py-1 rounded text-sm">{{ $zgloszenie->status->nazwa }}</span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-red-500 text-white px-2 py-1 rounded text-sm">{{ $zgloszenie->status->nazwa }}</span>
                            @endif
                        @endif
                    </div>
                    
                </div>
                
            @endforeach
        </div>
    </div>
</x-layouts::app>
