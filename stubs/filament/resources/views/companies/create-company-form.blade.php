<x-filament::card :header="__('Company Details')">

<p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
    {{ __('Create a new company to collaborate with others on projects.') }}
</p>
<x-filament::form wire:submit.prevent="createCompany">
    <div class="col-span-6 sm:col-span-4">
        <x-context-label value="{{ __('Company Owner') }}" />
            <div class="flex items-center mt-2">
                <img class="w-12 h-12 rounded-full object-cover" src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}">

                <div class="ml-4 leading-tight">
                    <div>{{ $this->user->name }}</div>
                    <div class="text-gray-700 text-sm">{{ $this->user->email }}</div>
                </div>
            </div>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-context-label for="name" value="{{ __('Company Name') }}" />
            <x-context-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="state.name" autofocus />
            <x-context-input-error for="name" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-filament::button>
            {{ __('Create') }}
        </x-filament::button>
    </x-slot>
</x-filament::form>
