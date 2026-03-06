<?php

namespace App\Support\Traits;

use App\Services\Media\UploadService;
use Illuminate\Http\Request;

trait HasImageUpload
{
    /**
     * Process image uploads from the request and merge paths into the data array.
     *
     * @param  Request  $request  The incoming request
     * @param  array  $data  The validated data array
     * @param  array<string, string>  $imageFields  Map of field name => upload folder
     * @param  array|null  $existingImages  Map of field name => existing file path (for updates)
     * @return array The data array with uploaded image paths merged in
     */
    protected function processImageUploads(
        Request $request,
        array $data,
        array $imageFields,
        ?array $existingImages = null,
    ): array {
        $uploadService = property_exists($this, 'uploadService') && $this->uploadService
            ? $this->uploadService
            : app(UploadService::class);

        foreach ($imageFields as $field => $folder) {
            if ($request->hasFile($field)) {
                $existingFile = $existingImages[$field] ?? null;
                $data[$field] = $uploadService->upload(
                    $request->file($field),
                    $folder,
                    $existingFile,
                );
            }
        }

        return $data;
    }
}
