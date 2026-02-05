<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrira rute za broadcasting
        // Middleware 'web' koristi standardnu session autentifikaciju
        Broadcast::routes(['middleware' => ['auth:sanctum']]);

        /*
         * Registriraj sve kanale koje koristiš.
         * Na primjer board kanali:
         */
        require base_path('routes/channels.php');
    }
}
