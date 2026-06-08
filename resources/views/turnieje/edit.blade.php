@include('partials.turniej-nav')

{{-- zawartość --}}
<x-layouts::app :title="__('Edytuj turniej szachowy')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <div class="px-4 flex items-center justify-between mx-auto">
            <h1 class="text-2xl font-bold tracking-tight center">Edytuj turniej szachowy</h1>
        </div>

        <div class="p-4 m-4 rounded-lg border border-gray-200 max-w-md mx-auto">

            <form action="{{ route('turnieje.update', $turniej) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                    <p>
                        <label for="nazwa" class="block mb-2 text-sm font-medium text-gray-300">Nazwa turnieju*</label>
                        <input value="{{ $turniej->nazwa }}" type="text" name="nazwa" placeholder="Nazwa turnieju" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white" required>
                    </p>
                    <p>
                        <label for="miejsce" class="block mb-2 text-sm font-medium text-gray-300">Miejsce*</label>
                        <input value="{{ $turniej->miejsce }}" type="text" name="miejsce" placeholder="Miejsce" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white" required>
                    </p>
                    <p>
                        <label for="data_rozpoczecia" class="block mb-2 text-sm font-medium text-gray-300">Data rozpoczęcia*</label>
                        <input value="{{ $turniej->data_rozpoczecia }}" type="date" name="data_rozpoczecia" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white" required>
                    </p>
                    <p>
                        <label for="data_zakonczenia" class="block mb-2 text-sm font-medium text-gray-300">Data zakończenia*</label>
                        <input value="{{ $turniej->data_zakonczenia }}" type="date" name="data_zakonczenia" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white" required>
                    </p>

                    <p>
                        <label for="liczba_rund" class="block mb-2 text-sm font-medium text-gray-300">Liczba rund*</label>
                        <input value="{{ $turniej->liczba_rund }}" type="number" name="liczba_rund" placeholder="Liczba rund" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white" required>
                    </p>
                    <p>
                        <label for="limit_zawodnikow" class="block mb-2 text-sm font-medium text-gray-300">Limit zawodników*</label>
                        <input value="{{ $turniej->limit_zawodnikow }}" type="number" name="limit_zawodnikow" placeholder="Limit zawodników" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white" required>
                    </p>
                    <p>
                        <label for="tempo_gry" class="block mb-2 text-sm font-medium text-gray-300">Tempo gry*</label>
                        <input value="{{ $turniej->tempo_gry }}" type="text" name="tempo_gry" placeholder="Tempo gry (np. 5+0)" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white" required>
                    </p>
                    <p>
                        <label for="status_id" class="block mb-2 text-sm font-medium text-gray-300">Status*</label>
                        <select name="status_id" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white" required>
                            @foreach($turniej_statusy as $status)
                                <option value="{{ $status->id }}" {{ $turniej->status_id == $status->id ? 'selected' : '' }}>
                                    {{ $status->nazwa }}
                                </option>
                            @endforeach
                        </select>
                    </p>
                    <p>
                        <label for="opis" class="block mb-2 text-sm font-medium text-gray-300">Opis</label>
                        <textarea value="{{ $turniej->opis }}" name="opis" rows="4" placeholder="Opis turnieju" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white"></textarea>
                    </p>
                    <p>
                        <label for="komunikat" class="block mb-2 text-sm font-medium text-gray-300">Komunikat</label>
                        @if($turniej->komunikat_path)
                            <p class="mb-2 text-xs text-gray-400">
                                Obecny plik:
                                <a href="{{ Storage::url($turniej->komunikat_path) }}" target="_blank" rel="noopener noreferrer" class="underline text-blue-400 hover:text-blue-300">
                                    {{ basename($turniej->komunikat_path) }}
                                </a>
                            </p>
                        @endif
                        <input type="file" name="komunikat" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white">
                        <input type="hidden" name="existing_komunikat_path" value="{{ $turniej->komunikat_path }}">
                    </p>
                    <p>
                        <input type="submit" value="Zaktualizuj turniej" class="w-full p-2 mb-4 border rounded-lg bg-main text-white cursor-pointer">
                        <input type="button" value="Anuluj" onclick="window.history.back();" class="w-full p-2 mb-4 border rounded-lg text-white cursor-pointer">
                    </p>
            </form>

            <form action="{{ route('turnieje.destroy', $turniej) }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Czy na pewno chcesz usunąć ten turniej?');">
                @csrf
                @method('DELETE')
                    <input type="submit" value="Usuń turniej" class="w-full p-2 mb-4 border rounded-lg bg-red-500 text-white cursor-pointer">
            </form>
        </div>

    </div>
</x-layouts::app>
