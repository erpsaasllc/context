<x-guest-layout>
    <x-context-authentication-card>
        <x-slot name="logo">
            <x-context-authentication-card-logo />
        </x-slot>

        <x-context-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="block">
                <x-context-label for="email" value="{{ __('Email') }}" />
                <x-context-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus />
            </div>

            <div class="mt-4">
                <x-context-label for="password" value="{{ __('Password') }}" />
                <x-context-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-context-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-context-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-context-button>
                    {{ __('Reset Password') }}
                </x-context-button>
            </div>
        </form>
    </x-context-authentication-card>
</x-guest-layout>
