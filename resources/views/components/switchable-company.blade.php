@props(['company', 'component' => 'context-dropdown-link'])

<form method="POST" action="{{ route('current-company.update') }}" x-data>
    @method('PUT')
    @csrf

    <!-- Hidden Company ID -->
    <input type="hidden" name="company_id" value="{{ $company->id }}">

    <x-dynamic-component :component="$component" href="#" x-on:click.prevent="$root.submit();">
        <div class="flex items-center">
            @if (Auth::user()->isCurrentCompany($company))
                <svg class="mr-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            @endif

            <div class="truncate">{{ $company->name }}</div>
        </div>
    </x-dynamic-component>
</form>
