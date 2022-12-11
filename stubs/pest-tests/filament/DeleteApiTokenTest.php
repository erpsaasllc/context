<?php

use App\Models\User;
use Illuminate\Support\Str;
use ERPSAAS\Context\Features;
use ERPSAAS\Context\Http\Livewire\ApiTokenManager;
use Livewire\Livewire;

test('api tokens can be deleted', function () {
    if (Features::hasCompanyFeatures()) {
        $this->actingAs($user = User::factory()->withPersonalCompany()->create());
    } else {
        $this->actingAs($user = User::factory()->create());
    }

    $token = $user->tokens()->create([
        'name' => 'Test Token',
        'token' => Str::random(40),
        'abilities' => ['create', 'read'],
    ]);

    Livewire::test(ApiTokenManager::class)
                ->set(['apiTokenIdBeingDeleted' => $token->id])
                ->call('deleteApiToken');

    expect($user->fresh()->tokens)->toHaveCount(0);
})->skip(function () {
    return ! Features::hasApiFeatures();
}, 'API support is not enabled.');
