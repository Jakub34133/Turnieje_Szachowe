<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main class="bg-chessboard dark:bg-chessboard-l">
        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>
