<?php

use App\Enums\Environment;
use Illuminate\Validation\Rules\Password;

function appServiceProviderPasswordProperty(Password $rule, string $property): mixed
{
    $reflection = new ReflectionClass($rule);
    $reflectionProperty = $reflection->getProperty($property);

    return $reflectionProperty->getValue($rule);
}

it('uses a minimum of 6 characters without strict requirements in testing', function () {
    app()->instance('env', Environment::TESTING->value);

    $passwordRule = Password::default();

    expect(appServiceProviderPasswordProperty($passwordRule, 'min'))->toBe(6);
    expect(appServiceProviderPasswordProperty($passwordRule, 'mixedCase'))->toBeFalse();
    expect(appServiceProviderPasswordProperty($passwordRule, 'numbers'))->toBeFalse();
    expect(appServiceProviderPasswordProperty($passwordRule, 'symbols'))->toBeFalse();
    expect(appServiceProviderPasswordProperty($passwordRule, 'uncompromised'))->toBeFalse();
});

it('enforces strict password requirements in production', function () {
    app()->instance('env', Environment::PRODUCTION->value);

    $passwordRule = Password::default();

    expect(appServiceProviderPasswordProperty($passwordRule, 'min'))->toBe(6);
    expect(appServiceProviderPasswordProperty($passwordRule, 'mixedCase'))->toBeTrue();
    expect(appServiceProviderPasswordProperty($passwordRule, 'numbers'))->toBeTrue();
    expect(appServiceProviderPasswordProperty($passwordRule, 'symbols'))->toBeTrue();
    expect(appServiceProviderPasswordProperty($passwordRule, 'uncompromised'))->toBeTrue();
});
