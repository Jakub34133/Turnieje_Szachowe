{{-- @include('partials.turniej-nav') --}}

<x-layouts::app :title="__('Zgłoszenia')">
    @if (session('error'))
        <div class="p-2 m-4 flex items-center justify-between whitespace-nowrap overflow-x-auto glass-2">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="p-2 m-4 flex items-center justify-between whitespace-nowrap overflow-x-auto glass-2">
            {{ session('success') }}
        </div>
    @endif

    <div class="p-4 flex w-full flex-1 flex-col">

        <div class="grid w-full grid-cols-5">
            <a href="{{ route('turnieje.show', $turniej) }}" class="glass-off p-2">
                Komunikat
            </a>
            <a href="{{ route('zawodnicy.index', [$turniej]) }}" class="glass-off p-2">
                Wyniki
            </a>
            <a href="#" class="glass-off p-2">
                Rundy
            </a>
            <a href="{{ route('turnieje.edit', $turniej) }}" class="glass-off p-2">
                Edytuj turniej
            </a>
            <a href="{{ route('turnieje.zgloszenia.index', $turniej) }}" class="glass p-2" style="border-bottom: none">
                Zgłoszenia
            </a>
        </div>
        <div class="p-4 flex w-full flex-1 flex-col glass" style="border-top: none;">
            <h1 class="m-2 p-2 text-2xl font-bold tracking-tight">Zgłoszenia</h1>

            <form action="{{ route('turnieje.zgloszenia.index', $turniej) }}" method="GET">
                <div class="px-4 flex items-center gap-4">
                    <p>
                        <label for="nazwa" class="block mb-2 text-sm font-medium text-gray-300">Zawodnik</label>
                        <input type="text" id="nazwa" name="nazwa" value="{{ request('nazwa') }}"
                            placeholder="Nazwa zawodnika"
                            class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white">
                    </p>
                    <p>
                        <label for="status_id" class="block mb-2 text-sm font-medium text-gray-300">Status</label>
                        <select name="status_id" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white">
                            <option value="">Wszystkie</option>
                            @foreach ($zgloszenia_statusy as $status)
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
                @if ($zgloszenia->isEmpty())
                    <p class="text-gray-300">Brak zgłoszeń do wyświetlenia.</p>
                @endif
                @foreach ($zgloszenia as $zgloszenie)
                    <div class="p-2 m-2 flex items-center justify-between whitespace-nowrap overflow-x-auto glass-2 ">
                        <div>
                            <p class="text-white flex items-center gap-3 whitespace-nowrap overflow-x-auto">
                                <form action={{ route('zawodnicy.show', $zgloszenie->zawodnik) }} method="GET">
                                    <button type="submit" class="inline-flex items-center gap-1 cursor-pointer">
                                        <flux:icon.user-circle class="text-main" /> {{ $zgloszenie->zawodnik->name }}
                                    </button>
                                </form>
                                
                                <span class="inline-flex items-center gap-1 text-gray-400"> &lpar;ranking:
                                    {{ $zgloszenie->zawodnik->pzszach_ranking }},</span>
                                <span class="inline-flex items-center gap-1 text-gray-400"> tytuł:
                                    {{ $zgloszenie->zawodnik->tytul }}&rpar;</span>
                            </p>
                            <p class="pt-1 text-gray-300 flex items-center gap-3 overflow-x-auto">
                                <span class="inline-flex items-center gap-1"> {{ $zgloszenie->komentarz }}</span>
                            </p>
                        </div>
                        <div class="flex gap-2 pr-2 items-center">
                            @if ($zgloszenie->status->nazwa === 'wysłane')
                                <form action="{{ route('turnieje.zgloszenia.approve', [$turniej, $zgloszenie]) }}"
                                    method="POST" enctype="multipart/form-data"
                                    onsubmit="return confirm('Czy na pewno chcesz zatwierdzić to zgłoszenie?');">
                                    @csrf
                                    @method('POST')
                                    <flux:button type="submit" class="bg-main" icon="check">
                                        Zatwierdź
                                    </flux:button>
                                </form>
                                <form action="{{ route('turnieje.zgloszenia.reject', [$turniej, $zgloszenie]) }}"
                                    method="POST" enctype="multipart/form-data"
                                    onsubmit="return confirm('Czy na pewno chcesz odrzucić to zgłoszenie?');">
                                    @csrf
                                    @method('POST')
                                    <flux:button type="submit" class="bg-red-500" icon="x-mark">
                                        Odrzuć
                                    </flux:button>
                                </form>
                            @else
                                @if ($zgloszenie->status->nazwa === 'zatwierdzone')
                                    <span
                                        class="inline-flex items-center gap-1 bg-main text-white px-2 py-1 rounded text-sm">{{ $zgloszenie->status->nazwa }}</span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 bg-red-500 text-white px-2 py-1 rounded text-sm">{{ $zgloszenie->status->nazwa }}</span>
                                @endif
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts::app>
