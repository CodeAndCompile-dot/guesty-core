<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class SettingComposer
{
    /**
     * Bind setting_data to every view.
     *
     * Reads all key-value pairs from the basic_settings table and caches them
     * for 60 minutes. Falls back gracefully if the table doesn't exist yet
     * (e.g. during initial migration or testing).
     */
    public function compose(View $view): void
    {
        $settings = Cache::remember('setting_data', 3600, function () {
            if (! $this->settingsTableExists()) {
                return collect();
            }

            return DB::table('basic_settings')
                ->pluck('value', 'name');
        });

        $view->with('setting_data', $settings);
    }

    /**
     * Check whether the basic_settings table exists.
     */
    protected function settingsTableExists(): bool
    {
        try {
            return Schema::hasTable('basic_settings');
        } catch (\Throwable) {
            return false;
        }
    }
}
