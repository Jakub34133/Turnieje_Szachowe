{{-- zawartość --}}
<x-layouts::app :title="$zawodnik->name">

    <div class="p-4 flex w-full flex-1 flex-col">
        <div class="grid w-full grid-cols-4">
            <a href="{{ route('zawodnicy.show', $zawodnik) }}" class="glass-off p-2">Profil</a>
            <a href="#" class="glass-off p-2">Historia rankingu</a>
            <a href="#" class="glass-off p-2">Historia turniejów</a>
            <a href="{{ route('tytuly.index', $zawodnik) }}" class="glass p-2" style="border-bottom: none">Historia
                tytułów</a>
        </div>

        <div class="p-4 flex w-full flex-1 flex-col glass" style="border-top: none;">

            <h1 class="m-2 p-2 text-2xl font-bold tracking-tight">{{ $zawodnik->name }}</h1>
            <h2 class="m-2 p-2 pt-0 mt-0 text-l text-gray-300 tracking-tight">Historia uzyskanych tytułów</h2>
            @foreach ($tytuly as $tytul)
                {{-- Tytul --}}
                <p class="p-2 m-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <span class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                        <flux:icon.trophy class="text-main" />{{ $tytul->tytul_typ }}
                    </span>
                    <span class="flex justify-between">
                        <span class="inline-flex items-center gap-1">
                            {{ $tytul->nazwa }}
                        </span>
                        <span class="inline-flex items-center gap-1 text-gray-400">
                            {{ $tytul->data_nadania }}
                        </span>
                    </span>

                </p>
            @endforeach
        </div>
    </div>
</x-layouts::app>
