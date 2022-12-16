<x-context::grid-section class="mt-8">
    <x-slot name="title">
        {{ __('Delete Account') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Permanently delete your account.') }}
    </x-slot>

    <div class="mt-10 sm:mt-0">
        <x-filament::card class="col-span-2 sm:col-span-1 mt-5 md:mt-0">

            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400 dark:text-gray-300">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
            </div>

            <div class="mt-5">
                <x-filament::button color="danger" wire:click="confirmUserDeletion" wire:loading.attr="disabled">
                    {{ __('Delete Account') }}
                </x-filament::button>
            </div>

            <!-- Delete User Confirmation Modal -->
            <x-context-dialog-modal wire:model="confirmingUserDeletion" maxWidth="md"
                class="flex items-center justify-center space-x-2 rtl:space-x-reverse">
                <x-slot name="title">
                    {{ __('Delete Account') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}

                    <div class="mt-4" x-data="{}"
                        x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                        <x-context-input type="password" class="mt-1 block w-3/4" placeholder="{{ __('Password') }}"
                            x-ref="password" wire:model.defer="password" wire:keydown.enter="deleteUser" />

                        <x-context-input-error for="password" class="mt-2" />
                    </div>
                </x-slot>

                <x-slot name="footer">
                    <x-filament::button color="gray" class="mr-3" wire:click="$toggle('confirmingUserDeletion')"
                        wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-filament::button>

                    <x-filament::button color="danger" class="ml-3" wire:click="deleteUser"
                        wire:loading.attr="disabled">
                        {{ __('Delete Account') }}
                    </x-filament::button>
                </x-slot>
            </x-context-dialog-modal>
        </x-filament::card>
</x-context::grid-section>
