<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    public function boot(): void
    {
        $this->routes(function () {
           
            Route::middleware('web')
                ->group(base_path('routes/web.php'));


            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));
        });

        $this->configureCustomRedirects();
    }

  
    protected function configureCustomRedirects(): void
    {
    
        $this->app->bind('path.afterLogin', function () {
            if (Auth::check()) {
                switch (Auth::user()->role) {
                    case 'admin':
                        return '/admin/dashboard';
                    case 'supervisor':
                        return '/supervisor/dashboard';
                    case 'customer':
                        return '/customer/dashboard';
                    default:
                        return self::HOME;
                }
            }

            return '/login';
        });
    }
}