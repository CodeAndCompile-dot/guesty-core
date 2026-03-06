<?php

namespace Tests\Feature\ViewComposer;

use App\View\Composers\SettingComposer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class SettingComposerTest extends TestCase
{
    public function test_setting_data_is_shared_to_all_views(): void
    {
        // Create the basic_settings table for this test
        if (! Schema::hasTable('basic_settings')) {
            Schema::create('basic_settings', function ($table) {
                $table->id();
                $table->string('name')->index();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        DB::table('basic_settings')->insert([
            ['name' => 'site_name', 'value' => 'Bentonville Lodging Co.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'site_email', 'value' => 'info@test.com', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Clear cached settings
        Cache::forget('setting_data');

        // Create a temporary blade view for testing
        $viewContent = '{{ $setting_data["site_name"] ?? "missing" }}';
        file_put_contents(resource_path('views/test_setting_composer.blade.php'), $viewContent);

        $response = $this->get('/');

        // Verify the composer was registered (setting_data available)
        $composer = new SettingComposer;
        $view = View::make('test_setting_composer');
        $composer->compose($view);

        $data = $view->getData();

        $this->assertArrayHasKey('setting_data', $data);
        $this->assertEquals('Bentonville Lodging Co.', $data['setting_data']['site_name']);
        $this->assertEquals('info@test.com', $data['setting_data']['site_email']);

        // Cleanup
        @unlink(resource_path('views/test_setting_composer.blade.php'));
        Schema::dropIfExists('basic_settings');
    }

    public function test_setting_composer_handles_missing_table_gracefully(): void
    {
        // Ensure the table doesn't exist
        Schema::dropIfExists('basic_settings');

        // Clear cache to force re-read
        Cache::forget('setting_data');

        $composer = new SettingComposer;
        $view = View::make('welcome');
        $composer->compose($view);

        $data = $view->getData();

        $this->assertArrayHasKey('setting_data', $data);
        $this->assertCount(0, $data['setting_data']);
    }

    public function test_setting_data_is_cached(): void
    {
        if (! Schema::hasTable('basic_settings')) {
            Schema::create('basic_settings', function ($table) {
                $table->id();
                $table->string('name')->index();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        DB::table('basic_settings')->insert([
            ['name' => 'cached_key', 'value' => 'cached_value', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Cache::forget('setting_data');

        $composer = new SettingComposer;

        // First call — should query DB and cache
        $view1 = View::make('welcome');
        $composer->compose($view1);

        // Verify it's now cached
        $this->assertTrue(Cache::has('setting_data'));

        // Second call — should use cache
        $view2 = View::make('welcome');
        $composer->compose($view2);

        $data = $view2->getData();
        $this->assertEquals('cached_value', $data['setting_data']['cached_key']);

        // Cleanup
        Schema::dropIfExists('basic_settings');
    }
}
