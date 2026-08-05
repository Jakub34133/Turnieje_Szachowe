@include('partials.turniej-nav')

{{-- zawartość --}}
<x-layouts::app :title="__('Edytuj turniej szachowy')">
    <div class="p-4 flex h-full w-full flex-1 flex-col glass">

        <h1 class="m-2 p-2 text-2xl font-bold tracking-tight">Edytuj turniej</h1>


        <div class="rounded-lg w-full mx-auto relative">

            <form id="turniej-update-form" action="{{ route('turnieje.update', $turniej) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2">
                    {{-- Nazwa --}}
                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                        <label for="nazwa" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.information-circle class="text-main"/> Nazwa turnieju*
                        </label>
                        <span class="inline-flex items-center">
                            <input value="{{ $turniej->nazwa }}" type="text" name="nazwa" id="nazwa" placeholder="Nazwa turnieju" class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20" required>
                        </span>
                    </p>
                    {{-- Miejsce --}}
                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                        <label for="miejsce" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.map class="text-main"/> Miejsce*
                        </label>
                        <span class="inline-flex items-center">
                            <input value="{{ $turniej->miejsce }}" type="text" name="miejsce" id="miejsce" placeholder="Miejsce" class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20" required>
                        </span>
                    </p>
                    {{-- Data rozpoczęcia --}}
                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                        <label for="data_rozpoczecia" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.calendar class="text-main"/> Data rozpoczęcia*
                        </label>
                        <span class="inline-flex items-center">
                            <input value="{{ $turniej->data_rozpoczecia }}" type="date" name="data_rozpoczecia" id="data_rozpoczecia" class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20 " required>
                        </span>
                    </p>
                    {{-- Data zakończenia --}}
                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                        <label for="data_zakonczenia" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.calendar class="text-main"/> Data zakończenia*
                        </label>
                        <span class="inline-flex items-center">
                            <input value="{{ $turniej->data_zakonczenia }}" type="date" name="data_zakonczenia" id="data_zakonczenia" class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20 " required>
                        </span>
                    </p>
                    {{-- Liczba rund --}}
                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                        <label for="liczba_rund" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.book-open class="text-main"/> Liczba rund*
                        </label>
                        <span class="inline-flex items-center">
                            <input value="{{ $turniej->liczba_rund }}" type="number" name="liczba_rund" id="liczba_rund" placeholder="Liczba rund" class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20 " required>
                        </span>
                    </p>
                    {{-- Limit zawodników --}}
                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                        <label for="limit_zawodnikow" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.user-group class="text-main"/> Limit zawodników*
                        </label>
                        <span class="inline-flex items-center">
                            <input value="{{ $turniej->limit_zawodnikow }}" type="number" name="limit_zawodnikow" id="limit_zawodnikow" placeholder="Limit zawodników" class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20 " required>
                        </span>
                    </p>
                    {{-- Tempo gry --}}
                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                        <label for="tempo_gry" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.clock class="text-main"/> Tempo gry*
                        </label>
                        <span class="inline-flex items-center">
                            <input value="{{ $turniej->tempo_gry }}" type="text" name="tempo_gry" id="tempo_gry" placeholder="Tempo gry (np. 10'+5'')" class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20 " required>
                        </span>
                    </p>
                    {{-- Status --}}
                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                        <label for="status_id" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.forward class="text-main"/> Status*
                        </label>
                        <span class="inline-flex items-center">
                            <select name="status_id" id="status_id" class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20 " required>
                                @foreach($turniej_statusy as $status)
                                    <option value="{{ $status->id }}" {{ $turniej->status_id == $status->id ? 'selected' : '' }} class="bg-black/75 ">
                                        {{ $status->nazwa }}
                                    </option>
                                @endforeach
                            </select>
                        </span>
                    </p>
                </div>
                
                <div class="grid">
                    {{-- Opis --}}
                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                        <label for="opis" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.chat-bubble-bottom-center-text class="text-main"/> Opis
                        </label>
                        <span class="inline-flex items-center">
                            <textarea name="opis" id="opis" rows="4" placeholder="Opis turnieju" class="w-full px-4 py-2 border rounded-2xl bg-black/20 border-white/20 ">{{ $turniej->opis }}</textarea>
                        </span>
                    </p>
                    {{-- Komunikat --}}
                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                        <label for="komunikat" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.document class="text-main"/> Komunikat
                        </label>
                        <span class="inline-flex flex-col">
                            @if($turniej->komunikat_path)
                            <span class="mb-2 text-xs text-gray-400">
                                Obecny plik:
                                <a href="{{ Storage::url($turniej->komunikat_path) }}" target="_blank" rel="noopener noreferrer" class="underline text-blue-400 hover:text-blue-300">
                                    {{ basename($turniej->komunikat_path) }}
                                </a>
                            </span>
                            @endif
                            <input type="file" name="komunikat" id="komunikat" class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20 ">
                            <input type="hidden" name="existing_komunikat_path" value="{{ $turniej->komunikat_path }}">
                        </span>
                    </p>
                </div>

                <div class="grid grid-cols-4 gap-3 m-2 justify-end items-center whitespace-nowrap overflow-x-auto">

                    <p class="col-start-3">
                        <input type="button" value="Anuluj" onclick="window.history.back();" class="w-full p-2 cursor-pointer bg-black/20 hover:bg-white/20 duration-200">
                    </p>
                    <p>
                        <input type="submit" value="Zaktualizuj turniej" class="w-full p-2 cursor-pointer bg-main hover:bg-main-2 duration-200">
                    </p>

                </div>
            </form>

            <div class="w-[50%] absolute left-0 bottom-0 grid grid-cols-2 gap-3 m-2 justify-end items-center whitespace-nowrap overflow-x-auto">

                <form action="{{ route('turnieje.destroy', $turniej) }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Czy na pewno chcesz usunąć ten turniej?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full p-2 cursor-pointer bg-red-500 hover:bg-red-600 duration-200">
                        Usuń turniej
                    </button>
                </form>
            </div>
        </div>

    </div>
</x-layouts::app>
