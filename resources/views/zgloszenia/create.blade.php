@include('partials.turniej-nav')

{{-- zawartość --}}
<x-layouts::app :title="__('Dodaj zgłoszenie')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <div class="px-4 flex items-center justify-between mx-auto">
            <h1 class="text-2xl font-bold tracking-tight center">Dodaj zgłoszenie do turnieju</h1>
        </div>

        <div class="p-4 m-4 rounded-lg border border-gray-200 max-w-md min-w-md mx-auto">

            <form action="{{ route('turnieje.zgloszenia.store', $turniej) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                    
                    <p>
                        <label for="komentarz" class="block mb-2 text-sm font-medium text-gray-300">Komentarz</label>
                        <textarea name="komentarz" rows="4" placeholder="Komentarz" class="w-full p-2 mb-4 border rounded-lg bg-gray-800 text-white"></textarea>
                    </p>
                    
                    <p>
                        <input type="submit" value="Zgłoś się" class="w-full p-2 mb-4 border rounded-lg bg-main text-white cursor-pointer">
                        <input type="button" value="Anuluj" onclick="window.history.back();" class="w-full p-2 mb-4 border rounded-lg text-white cursor-pointer">
                    </p>
            </form>
        </div>

    </div>
</x-layouts::app>
