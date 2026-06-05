<x-layouts::app :title="__('Utwórz turniej szachowy')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="px-4 flex items-center justify-between mx-auto">
            <h1 class="text-2xl font-bold tracking-tight">{{ __('Utwórz turniej szachowy') }}</h1>
            
        </div>
        
        <form action="{{ route('turnieje.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-4 m-4 rounded-lg border border-gray-200 max-w-md mx-auto">
                    <p>
                    <label for="nazwa" class="block mb-2 text-sm font-medium text-gray-300">Nazwa turnieju*</label>
                    <input type="text" name="nazwa" placeholder="Nazwa turnieju" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white" required>
                </p>
                <p>
                    <label for="miejsce" class="block mb-2 text-sm font-medium text-gray-300">Miejsce*</label>
                    <input type="text" name="miejsce" placeholder="Miejsce" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white" required>
                </p>
                <p>
                    <label for="data_rozpoczecia" class="block mb-2 text-sm font-medium text-gray-300">Data rozpoczęcia*</label>
                    <input type="date" name="data_rozpoczecia" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white" required>
                </p>
                <p>
                    <label for="data_zakonczenia" class="block mb-2 text-sm font-medium text-gray-300">Data zakończenia*</label>
                    <input type="date" name="data_zakonczenia" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white" required>
                </p>

                <p>
                    <label for="liczba_rund" class="block mb-2 text-sm font-medium text-gray-300">Liczba rund*</label>
                    <input type="number" name="liczba_rund" placeholder="Liczba rund" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white" required>
                </p>
                <p>
                    <label for="limit_zawodnikow" class="block mb-2 text-sm font-medium text-gray-300">Limit zawodników*</label>
                    <input type="number" name="limit_zawodnikow" placeholder="Limit zawodników" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white" required>
                </p>
                <p>
                    <label for="tempo_gry" class="block mb-2 text-sm font-medium text-gray-300">Tempo gry*</label>
                    <input type="text" name="tempo_gry" placeholder="Tempo gry (np. 5+0)" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white" required>
                </p>
                <p>
                    <label for="opis" class="block mb-2 text-sm font-medium text-gray-300">Opis</label>
                    <textarea name="opis" rows="4" placeholder="Opis turnieju" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white"></textarea>
                </p>
                <p>
                    <label for="komunikat" class="block mb-2 text-sm font-medium text-gray-300">Komunikat</label>
                    <input type="file" name="komunikat" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white">
                </p>
                <p>
                    <input type="submit" value="Utwórz turniej" class="w-full p-2 mb-4 border rounded-lg bg-main text-white cursor-pointer">
                    <input type="button" value="Anuluj" onclick="window.history.back();" class="w-full p-2 mb-4 border rounded-lg bg-red-500 text-white cursor-pointer">
                </p>
            </div>
        </form>

</x-layouts::app>
