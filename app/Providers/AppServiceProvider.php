<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        // Gates based on user roles
        Gate::define('admin-only', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('guru-only', function (User $user) {
            return $user->isGuru();
        });

        Gate::define('siswa-only', function (User $user) {
            return $user->isSiswa();
        });

        Gate::define('manage-master', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-absensi', function (User $user) {
            return in_array($user->role, ['admin', 'guru']);
        });

        Gate::define('manage-nilai', function (User $user) {
            return $user->isGuru();
        });

        Gate::define('view-nilai', function (User $user) {
            return in_array($user->role, ['admin', 'guru', 'siswa']);
        });
    }
}
