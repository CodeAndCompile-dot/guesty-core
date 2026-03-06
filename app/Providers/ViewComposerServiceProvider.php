<?php

namespace App\Providers;

use App\View\Composers\SettingComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share setting_data with all views — replaces View::share() in old AppServiceProvider
        View::composer('*', SettingComposer::class);
    }
}
