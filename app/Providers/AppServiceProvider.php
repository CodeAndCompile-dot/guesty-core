<?php

namespace App\Providers;

use App\Facades\Helper;
use App\Facades\LiveCart as LiveCartFacade;
use App\Facades\ModelHelper;
use App\Helpers\Helper as HelperImpl;
use App\Helpers\LiveCart as LiveCartImpl;
use App\Helpers\ModelHelper as ModelHelperImpl;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('ModelHelper', fn () => new ModelHelperImpl());
        $this->app->bind('Helper', fn () => new HelperImpl());
        $this->app->bind('LiveCart', fn ($app) => $app->make(LiveCartImpl::class));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('ModelHelper', ModelHelper::class);
        $loader->alias('Helper', Helper::class);
        $loader->alias('LiveCart', LiveCartFacade::class);
    }
}
