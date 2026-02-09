<?php

use App\Enums\Role;
use Illuminate\Support\Facades\Route;

Route::get('health', Spatie\Health\Http\Controllers\HealthCheckResultsController::class)->middleware(['auth', 'role:'.Role::SUPER_ADMIN->value]);

Route::livewire('elements', 'pages::elements')->middleware(['auth', 'role:'.Role::SUPER_ADMIN->value])->name('elements');

Route::livewire('login', 'pages::login.show')->middleware(['guest'])->name('login');

Route::post('logout', App\Livewire\Actions\Logout::class)->middleware(['auth'])->name('logout');

Route::livewire('forgot-password', 'pages::password.show')->middleware(['guest', 'throttle:6,1'])->name('password');
Route::livewire('reset-password/{token}', 'pages::password.reset.[token]')->middleware(['guest', 'throttle:6,1'])->name('password.reset');

Route::livewire('register', 'pages::register.show')->middleware(['guest'])->name('register');

Route::livewire('', 'pages::dashboard.index')->middleware(['auth', 'verified'])->name('home');

Route::livewire('account', 'pages::account.edit')->middleware(['auth', 'verified'])->name('account.edit');

Route::livewire('account/verify', 'pages::verification.show')->middleware(['auth'])->name('verification.notice');
Route::livewire('account/verify/{id}/{hash}', 'pages::verification.show')->middleware(['auth', 'signed'])->name('verification.verify');
