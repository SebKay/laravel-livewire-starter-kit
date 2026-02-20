<?php

use App\Enums\Permission;
use App\Enums\Role;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\Models\Role as SpatieRole;

it('syncs roles and permissions from enums via artisan command', function () {
    $this->artisan('permissions:sync')
        ->assertSuccessful();

    expect(SpatieRole::count())->toBe(count(Role::cases()));
    expect(SpatiePermission::count())->toBe(count(Permission::cases()));
});

it('truncates existing records when running with fresh option', function () {
    SpatieRole::query()->create(['name' => 'legacy-role']);
    SpatiePermission::query()->create(['name' => 'legacy-permission']);

    $this->artisan('permissions:sync --fresh')
        ->assertSuccessful();

    expect(SpatieRole::query()->where('name', 'legacy-role')->exists())->toBeFalse();
    expect(SpatiePermission::query()->where('name', 'legacy-permission')->exists())->toBeFalse();
});
