<?php

namespace App\Services\Admin;

use App\Repositories\Contracts\SettingRepositoryInterface;
use App\Services\Media\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    /**
     * Fields that require file uploads (field name => upload folder).
     */
    protected array $fileFields = [
        'favicon' => 'settings',
        'header_logo' => 'settings',
        'footer_logo' => 'settings',
        'owner_image' => 'settings',
        'ogimage' => 'settings',
    ];

    protected array $videoFields = [
        'home_video' => 'settings',
    ];

    public function __construct(
        protected SettingRepositoryInterface $repository,
        protected UploadService $uploadService,
    ) {
    }

    /**
     * Get all settings as key-value collection.
     */
    public function getAll(): Collection
    {
        return $this->repository->getAll();
    }

    /**
     * Save settings from the request.
     *
     * The legacy controller iterates over $request->input (an assoc array of name => value),
     * strips single quotes from keys, checks if the key matches a file/video field,
     * and upserts each key-value pair into basic_settings.
     */
    public function saveSettings(Request $request): void
    {
        $inputs = $request->input('input', []);

        foreach ($inputs as $key => $value) {
            // Legacy strips single quotes from keys
            $cleanKey = str_replace("'", '', $key);

            // Check if this is a file upload field
            if (array_key_exists($cleanKey, $this->fileFields) && $request->hasFile("input.{$key}")) {
                $existingValue = $this->repository->getValue($cleanKey);
                $path = $this->uploadService->upload(
                    $request->file("input.{$key}"),
                    $this->fileFields[$cleanKey],
                    $existingValue,
                );
                $this->repository->setValue($cleanKey, $path);
            } elseif (array_key_exists($cleanKey, $this->videoFields) && $request->hasFile("input.{$key}")) {
                $existingValue = $this->repository->getValue($cleanKey);
                $path = $this->uploadService->upload(
                    $request->file("input.{$key}"),
                    $this->videoFields[$cleanKey],
                    $existingValue,
                );
                $this->repository->setValue($cleanKey, $path);
            } else {
                $this->repository->setValue($cleanKey, $value);
            }
        }

        Cache::forget('setting_data');
    }
}
