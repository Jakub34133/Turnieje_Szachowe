<x-layouts::app.sidebar :title="$title ?? null">
    <div class="flex min-h-screen flex-col justify-between">
        @include('partials.app-header')
        <flux:main class="flex-1 bg-chessboard dark:bg-chessboard-l">
            {{ $slot }}
        </flux:main>
        @include('partials.app-footer')
    </div>
</x-layouts::app.sidebar>
