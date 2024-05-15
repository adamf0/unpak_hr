<?php

namespace Architecture\External\Config\Provider;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            // return Limit::perMinute(1200)->by($request->user()?->id ?: $request->ip());
            return Limit::none();
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('architecture/External/Api/Endpoints/api.php'));

            Route::middleware('api')
                ->prefix('datatable')
                ->group(base_path('architecture/External/Datatable/Endpoints/datatable.php'));

            Route::middleware('api')
                ->prefix('select2')
                ->group(base_path('architecture/External/Select2/Endpoints/select2.php'));

            Route::middleware('web')
                ->group(base_path('architecture/External/Web/Endpoints/web.php'));
        });
    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('none', function (Request $request) {
            return Limit::none();
        });
    }
}
