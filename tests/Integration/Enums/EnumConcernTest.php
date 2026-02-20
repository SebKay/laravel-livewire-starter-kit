<?php

use App\Enums\Role;

it('returns enum values as a collection', function () {
    expect(Role::values()->all())->toBe([
        Role::SUPER_ADMIN->value,
        Role::ADMIN->value,
        Role::USER->value,
    ]);
});

it('returns matching enum cases when using only by default', function () {
    $cases = Role::only([Role::SUPER_ADMIN, Role::USER]);

    expect($cases->values()->all())->toBe([
        Role::SUPER_ADMIN,
        Role::USER,
    ]);
});

it('returns matching enum values when using only as array', function () {
    $values = Role::only([Role::SUPER_ADMIN, Role::USER], asArray: true);

    expect($values->values()->all())->toBe([
        Role::SUPER_ADMIN->value,
        Role::USER->value,
    ]);
});
