<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
            // Daftarkan UserPolicy untuk Model User
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        // --- TAMBAHKAN BLOK INI ---
        // Mendefinisikan Gate untuk dashboard customer
        Gate::define('access-customer-dashboard', function (User $user) {
            return $user->role === 'customer';
        });
        // --- AKHIR BLOK TAMBAHAN ---
    }
}