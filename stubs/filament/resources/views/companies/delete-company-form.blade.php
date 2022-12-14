<x-context-action-section>
    <x-slot name="title">
        {{ __('Delete Company') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Permanently delete this company.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('Once a company is deleted, all of its resources and data will be permanently deleted. Before deleting this company, please download any data or information regarding this company that you wish to retain.') }}
        </div>

        <div class="mt-5">
            <x-filament::button wire:click="$toggle('confirmingCompanyDeletion')" wire:loading.attr="disabled">
                {{ __('Delete Company') }}
            </x-filament::button>
        </div>

        <!-- Delete Company Confirmation Modal -->
        <x-context-confirmation-modal wire:model="confirmingCompanyDeletion">
            <x-slot name="title">
                {{ __('Delete Company') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete this company? Once a company is deleted, all of its resources and data will be permanently deleted.') }}
            </x-slot>

            <x-slot name="footer">
                <x-filament::button wire:click="$toggle('confirmingCompanyDeletion')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-filament::button>

                <x-filament::button class="ml-3" wire:click="deleteCompany" wire:loading.attr="disabled">
                    {{ __('Delete Company') }}
                </x-filament::button>
            </x-slot>
        </x-context-confirmation-modal>
    </x-slot>
</x-context-action-section>
