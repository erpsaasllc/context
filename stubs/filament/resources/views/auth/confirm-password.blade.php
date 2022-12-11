<x-guest-layout>
    <x-context::authentication-card>
        <x-slot name="logo">
            <x-context::authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>

        <x-context::validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div>
                <x-context::label for="password" value="{{ __('Password') }}" />
                <x-context::input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" autofocus />
            </div>

            <div class="flex justify-end mt-4">
                <x-context::button class="ml-4">
                    {{ __('Confirm') }}
                </x-context::button>
            </div>
        </form>
    </x-context::authentication-card>
</x-guest-layout>
