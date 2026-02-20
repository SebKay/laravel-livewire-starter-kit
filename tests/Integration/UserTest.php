<?php

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Hash;

it("can update it's password", function () {
    $user = User::factory()->create([
        'password' => Hash::make('oldPassword#123'),
    ]);

    expect(Hash::check('oldPassword#123', $user->password))->toBeTrue();

    $user->updatePassword('newPassword#123');

    expect(Hash::check('newPassword#123', $user->refresh()->password))->toBeTrue();
});

it("doesn't update it's password if the value is empty", function () {
    $user = User::factory()->create([
        'password' => Hash::make('oldPassword#123'),
    ]);

    expect(Hash::check('oldPassword#123', $user->password))->toBeTrue();

    $user->updatePassword('');

    expect(Hash::check('oldPassword#123', $user->refresh()->password))->toBeTrue();

    $user->updatePassword(null);

    expect(Hash::check('oldPassword#123', $user->refresh()->password))->toBeTrue();
});

describe('With Roles and Permissions', function () {
    beforeEach(function () {
        $this->seed(RolesAndPermissionsSeeder::class);
    });

    it("can only access Filament if it's a \"super-admin\" or \"admin\"", function () {
        $superAdminUser = User::factory()->superAdmin()->create();
        $adminUser = User::factory()->admin()->create();
        $user = User::factory()->user()->create();

        expect($superAdminUser->canAccessPanel())->toBeTrue();
        expect($adminUser->canAccessPanel())->toBeTrue();
        expect($user->canAccessPanel())->toBeFalse();
    });

    it('returns all permission names through the all_permissions accessor', function () {
        $user = User::factory()->create();
        $user->assignRole(Role::SUPER_ADMIN);

        expect($user->all_permissions->all())->toContain(Permission::ACCESS_ADMIN->value);
        expect($user->all_permissions)->toHaveCount(count(Role::SUPER_ADMIN->permissions()));
    });

    it('can scope users by role names', function () {
        $superAdminUser = User::factory()->create();
        $superAdminUser->assignRole(Role::SUPER_ADMIN);

        $regularUser = User::factory()->create();
        $regularUser->assignRole(Role::USER);

        $scopedUsers = User::query()
            ->hasRoles([Role::SUPER_ADMIN->value, Role::ADMIN->value])
            ->pluck('id');

        expect($scopedUsers->contains($superAdminUser->id))->toBeTrue();
        expect($scopedUsers->contains($regularUser->id))->toBeFalse();
    });
});

it('returns the filament display name', function () {
    $user = User::factory()->create([
        'name' => 'Coverage User',
    ]);

    expect($user->getFilamentName())->toBe('Coverage User');
});
