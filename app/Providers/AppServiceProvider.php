<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Smpita\TypeAs\TypeAs;

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
        Model::shouldBeStrict(! $this->app->isProduction());
        Model::automaticallyEagerLoadRelationships();

        Vite::useAggressivePrefetching();
        Vite::macro('image', fn (string $asset) => TypeAs::class(\Illuminate\Foundation\Vite::class, $this)->asset("resources/images/{$asset}"));

        Date::use(CarbonImmutable::class);
    }
}
