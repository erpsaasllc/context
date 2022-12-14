<x-filament::page>
        @if (Laravel\Fortify\Features::canUpdateProfileInformation())
            <div class="mt-10 sm:mt-0">
                @livewire('profile.update-profile-information-form')
            </div>

            <x-context-section-border />
        @endif

        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
            <div class="mt-10 sm:mt-0">
                @livewire('profile.update-password-form')
            </div>

            <x-context-section-border />
        @endif

        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
            <div class="mt-10 sm:mt-0">
                @livewire('profile.two-factor-authentication-form')
            </div>

            <x-context-section-border />
        @endif

        <div class="mt-10 sm:mt-0">
            @livewire('profile.logout-other-browser-sessions-form')
        </div>

        @if (ERPSAAS\Context\Context::hasAccountDeletionFeatures())
            <x-context-section-border />

            <div class="mt-10 sm:mt-0">
                @livewire('profile.delete-user-form')
            </div>
        @endif
</x-filament::page>
