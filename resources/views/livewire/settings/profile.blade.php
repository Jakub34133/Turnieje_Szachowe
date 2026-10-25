<section class="w-full">
    {{-- @include('partials.settings-heading') --}}

    <flux:heading class="sr-only">{{ __('Profile settings') }}</flux:heading>

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
        <form wire:submit="updateProfileInformation" class=" w-full space-y-6">
            <div class="grid grid-cols-2">


                {{-- Imie i nazwisko --}}
                <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <label for="name" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                        <flux:icon.identification class="text-main" /> Imie i nazwisko*
                    </label>
                    <span class="inline-flex items-center">
                        <input id="name" wire:model="name" type="text" placeholder="Imie i nazwisko"
                            class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20 " required
                            autofocus>
                    </span>
                </p>

                {{-- Płeć --}}
                <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <label for="plec" class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                        <flux:icon.identification class="text-main" /> Płeć*
                    </label>
                    <span class="inline-flex items-center">
                        <select id="plec" wire:model="plec"
                            class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20 " required>
                            <option value="M" class="bg-black/75 ">
                                Mężczyzna
                            </option>
                            <option value="K" class="bg-black/75 ">
                                Kobieta
                            </option>
                        </select>
                    </span>
                </p>

                {{-- Email --}}
                <div>
                    <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                        <label for="email"
                            class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                            <flux:icon.envelope-open class="text-main" /> Email*
                        </label>
                        <span class="inline-flex items-center">
                            <input id="email" wire:model="email" type="email" placeholder="Email"
                                class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20 " required
                                autocomplete="email">
                        </span>
                    </p>

                    @if ($this->hasUnverifiedEmail)
                        <div>
                            <flux:text class="mt-4">
                                {{ __('Your email address is unverified.') }}

                                <flux:link class="text-sm cursor-pointer"
                                    wire:click.prevent="resendVerificationNotification">
                                    {{ __('Click here to re-send the verification email.') }}
                                </flux:link>
                            </flux:text>

                        </div>
                    @endif
                </div>

                {{-- Data urodzenia --}}
                <p class="p-2 m-2 gap-2 grid items-center whitespace-nowrap overflow-x-auto glass-2 ">
                    <label for="data_urodzenia"
                        class="inline-flex items-center gap-1 text-main uppercase text-sm font-black">
                        <flux:icon.cake class="text-main" /> Data urodzenia*
                    </label>
                    <span class="inline-flex items-center">
                        <input id="data_urodzenia" wire:model="data_urodzenia" type="date"
                            placeholder="Data urodzenia"
                            class="w-full px-4 py-2 border rounded-full bg-black/20 border-white/20 " required>
                    </span>
                </p>

            </div>
            <div class="grid grid-cols-4 gap-3 m-2 justify-end items-center whitespace-nowrap overflow-x-auto">
                <p class="col-start-4">
                    <input type="submit" value="Zapisz"
                        class="w-full p-2 cursor-pointer bg-main hover:bg-main-2 duration-200">
                </p>
            </div>
        </form>

        {{-- @if ($this->showDeleteUser)
            <livewire:settings.delete-user-form />
        @endif --}}
    </x-settings.layout>
</section>
