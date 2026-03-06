<?php

namespace Tests\Unit\Services\Admin;

use App\Models\BasicSetting;
use App\Repositories\Contracts\SettingRepositoryInterface;
use App\Repositories\Eloquent\SettingRepository;
use App\Services\Admin\SettingService;
use App\Services\Media\UploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SettingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SettingService $service;

    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('basic_settings')) {
            Schema::create('basic_settings', function ($table) {
                $table->id();
                $table->string('name');
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        $repository = new SettingRepository(new BasicSetting);
        $uploadService = $this->createMock(UploadService::class);
        $this->service = new SettingService($repository, $uploadService);
    }

    public function test_save_settings_creates_new_settings(): void
    {
        $request = Request::create('/setting', 'POST', [
            'input' => [
                'site_name' => 'Test Site',
                'site_email' => 'test@test.com',
            ],
        ]);

        $this->service->saveSettings($request);

        $this->assertDatabaseHas('basic_settings', ['name' => 'site_name', 'value' => 'Test Site']);
        $this->assertDatabaseHas('basic_settings', ['name' => 'site_email', 'value' => 'test@test.com']);
    }

    public function test_save_settings_updates_existing_settings(): void
    {
        BasicSetting::create(['name' => 'site_name', 'value' => 'Old Name']);

        $request = Request::create('/setting', 'POST', [
            'input' => [
                'site_name' => 'New Name',
            ],
        ]);

        $this->service->saveSettings($request);

        $this->assertDatabaseHas('basic_settings', ['name' => 'site_name', 'value' => 'New Name']);
        $this->assertEquals(1, BasicSetting::where('name', 'site_name')->count());
    }

    public function test_save_settings_strips_single_quotes_from_keys(): void
    {
        $request = Request::create('/setting', 'POST', [
            'input' => [
                "'site_name'" => 'Quoted Key',
            ],
        ]);

        $this->service->saveSettings($request);

        $this->assertDatabaseHas('basic_settings', ['name' => 'site_name', 'value' => 'Quoted Key']);
    }

    public function test_save_settings_clears_cache(): void
    {
        Cache::put('setting_data', ['old' => 'data'], 3600);

        $request = Request::create('/setting', 'POST', [
            'input' => ['key' => 'value'],
        ]);

        $this->service->saveSettings($request);

        $this->assertNull(Cache::get('setting_data'));
    }

    public function test_get_all_returns_settings_collection(): void
    {
        BasicSetting::create(['name' => 'key1', 'value' => 'val1']);
        BasicSetting::create(['name' => 'key2', 'value' => 'val2']);

        $settings = $this->service->getAll();

        $this->assertEquals('val1', $settings['key1']);
        $this->assertEquals('val2', $settings['key2']);
    }
}
