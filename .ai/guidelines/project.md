# Project Overview

- This repository is a Laravel 12 starter kit centered on Livewire Volt pages and a small Filament admin panel. There are no app controllers or API routes in the current codebase; most user-facing behavior lives in `resources/views/pages/**/⚡*.blade.php`.
- Public web routes are declared in `routes/web.php`. Keep route names stable because both Volt tests and UI links depend on them.

# Auth And User Flow

- Guest auth flows live in Volt single-file components: `resources/views/pages/login/⚡show.blade.php`, `resources/views/pages/register/⚡show.blade.php`, `resources/views/pages/password/⚡show.blade.php`, and `resources/views/pages/password/reset/⚡[token].blade.php`.
- Authenticated account flows live in `resources/views/pages/dashboard/⚡index.blade.php`, `resources/views/pages/account/⚡edit.blade.php`, and `resources/views/pages/verification/⚡show.blade.php`.
- Home (`/`) and account (`/account`) require `auth` and `verified`. Email verification notice/confirm routes are separate and the confirm route is signed.
- Logout is not a controller; `POST /logout` resolves `App\Livewire\Actions\Logout`, which logs out, regenerates the CSRF token, invalidates the session, and redirects to `login`.
- Login and Filament login auto-fill the seeded super-user credentials only in `local` and `testing` via `config/seed.php`.

# Roles And Permissions

- Roles and permissions are code-defined enums in `app/Enums/Role.php` and `app/Enums/Permission.php`. Treat those enums as the source of truth.
- Database sync is handled by `App\Services\RolesAndPermissionsService` and the `permissions:sync` command in `app/Console/Commands/SyncRolesAndPermissionsCommand.php`.
- If you add or rename a role/permission, update all of these together: the enum case, `Role::permissions()`, seeded users/factory states if needed, and tests in `tests/Integration/Services/RolesAndPermissionsServiceTest.php` plus `tests/Integration/Console/SyncRolesAndPermissionsCommandTest.php`.
- `App\Models\User` uses Spatie `HasRoles`, exposes an `all_permissions` accessor, and gates Filament access in `canAccessPanel()` to `Role::SUPER` only.
- The `UserFactory` states `super()` and `user()` assign roles in `afterCreating()`. Seeders rely on roles being synced before users are created, so keep `RolesAndPermissionsSeeder` ahead of `UsersSeeder`.

# Filament Surface

- The admin panel is mounted at `/admin` in `app/Providers/Filament/AdminPanelProvider.php` and currently uses `App\Filament\Pages\Auth\Login` plus the default dashboard page `App\Filament\Pages\Dashboard`.
- `app/Filament/Resources/Users` is the only current resource. Password hashing happens in the page classes (`CreateUser`, `EditUser`), not in the schema object.
- `UserForm` edits `roles` through the Spatie relationship. `UsersTable` supports global search on `roles.name` and filter/search behavior depends on the relationship name staying `roles`.
- Dashboard widgets query `User` records directly. `UserActivityChart` performs one count query per day over the last 30 days, so avoid extending it in a way that multiplies query volume without reworking the data source.

# App-Wide Behavior And Side Effects

- `app/Providers/AppServiceProvider.php` forces HTTPS in `production` and `staging`, enables `Model::automaticallyEagerLoadRelationships()`, configures stricter password defaults outside `local/testing`, and registers Spatie health checks.
- Health checks are the only scheduled background work in the repo today: `routes/console.php` runs `Spatie\Health\Commands\RunHealthChecksCommand` every minute in `production`.
- There are no custom jobs, listeners, mailables, or notifications in `app/` right now. Password reset and email verification side effects come from Laravel’s built-in auth notifications triggered by the Volt pages.
- Toast UX is centralized in `App\Livewire\Concerns\InteractsWithToasts` and rendered by `resources/views/components/toast-stack.blade.php`. Account updates dispatch a browser event; password flows flash toast payloads into the session before redirecting.

# Testing Layout

- Volt page tests live next to the page components as `resources/views/pages/**/⚡*.test.php`; keep those paired with behavior changes.
- Cross-cutting model/service/command coverage lives under `tests/Integration`. Architecture assertions are in `tests/Architecture/AppTest.php`.
