<x-context::form-section submit="updatePassword">
    <x-slot name="title">
        {{ __('Update Password') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-context::label for="current_password" value="{{ __('Current Password') }}" />
            <x-context::input id="current_password" type="password" class="mt-1 block w-full" wire:model.defer="state.current_password" autocomplete="current-password" />
            <x-context::input-error for="current_password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-context::label for="password" value="{{ __('New Password') }}" />
            <x-context::input id="password" type="password" class="mt-1 block w-full" wire:model.defer="state.password" autocomplete="new-password" />
            <x-context::input-error for="password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-context::label for="password_confirmation" value="{{ __('Confirm Password') }}" />
            <x-context::input id="password_confirmation" type="password" class="mt-1 block w-full" wire:model.defer="state.password_confirmation" autocomplete="new-password" />
            <x-context::input-error for="password_confirmation" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-context::action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-context::action-message>

        <x-context::button>
            {{ __('Save') }}
        </x-context::button>
    </x-slot>
</x-context::form-section>
