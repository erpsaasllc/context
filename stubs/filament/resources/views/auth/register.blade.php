<x-guest-layout>
    <x-context-authentication-card>
        <x-slot name="logo">
            <x-context-authentication-card-logo />
        </x-slot>

        <x-context-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-context-label for="name" value="{{ __('Name') }}" />
                <x-context-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-context-label for="email" value="{{ __('Email') }}" />
                <x-context-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="mt-4">
                <x-context-label for="password" value="{{ __('Password') }}" />
                <x-context-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-context-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-context-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (ERPSAAS\Context\Context::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-context-label for="terms">
                        <div class="flex items-center">
                            <x-context-checkbox name="terms" id="terms" required />

                            <div class="ml-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-context-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-context-button class="ml-4">
                    {{ __('Register') }}
                </x-context-button>
            </div>
        </form>
    </x-context-authentication-card>
</x-guest-layout>
