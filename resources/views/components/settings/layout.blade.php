@php
    $currentRoute = Route::currentRouteName();
@endphp
<div class="flex h-full w-full flex-1 flex-col">
    <div class="w-full">
        <flux:navlist aria-label="{{ __('Settings') }}" class="flex-row m-0">
            <flux:navlist.item class="{{ $currentRoute === 'profile.edit' ? 'glass' : 'glass-off' }} rounded-none text-center" :href="route('profile.edit')" wire:navigate>{{ __('Ogólne') }}</flux:navlist.item>
            <flux:navlist.item class="{{ $currentRoute === 'security.edit' ? 'glass' : 'glass-off' }} rounded-none text-center" :href="route('security.edit')" wire:navigate>{{ __('Bezpieczeństwo') }}</flux:navlist.item>
            <flux:navlist.item class="{{ $currentRoute === '' ? 'glass' : 'glass-off' }} rounded-none text-center" wire:navigate>{{ __('Powiadomienia') }}</flux:navlist.item>
            <flux:navlist.item class="{{ $currentRoute === 'appearance.edit' ? 'glass' : 'glass-off' }} rounded-none text-center" :href="route('appearance.edit')" wire:navigate>{{ __('Personalizacja') }}</flux:navlist.item>
        </flux:navlist>
    </div>

    <div class="p-4 flex h-full w-full flex-1 flex-col glass border-t-none">
        <h1 class="m-2 p-2 text-2xl font-bold tracking-tight">Ustawienia</h1>
        {{-- <flux:heading>{{ $heading ?? '' }}</flux:heading> --}}
        {{-- <flux:subheading>{{ $subheading ?? '' }}</flux:subheading> --}}

        <div class="grid">
            {{ $slot }}
        </div>
    </div>
</div>
