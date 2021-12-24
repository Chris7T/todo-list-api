<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{

    public const HOME = '/home';

    protected $namespaceAuth    = 'App\\Http\\Controllers\\User';
    protected $namespaceProject = 'App\\Http\\Controllers\\Project';
    protected $namespaceTask    = 'App\\Http\\Controllers\\Task';

    public function boot()
    {
        $this->configureRateLimiting();

        $this->carregarArquivosRotas();
    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    private function carregarArquivosRotas()
    {
        $this->routes(
            function () {
                Route::namespace($this->namespaceAuth)->group(base_path('routes/user.php'));
                Route::middleware('api')->namespace($this->namespaceProject)->group(base_path('routes/project.php'));
                Route::middleware('api')->namespace($this->namespaceTask)->group(base_path('routes/task.php'));
            }
        );
    }
}
