<flux:header  class="border-b border-white/10 bg-main text-white">
    <x-app-logo href="{{ route('turnieje.index') }}" wire:navigate class="text-white" />

    {{-- <flux:navbar class="ms-6">
        
    </flux:navbar> --}}
    <flux:spacer />

    <x-desktop-user-menu />
</flux:header>