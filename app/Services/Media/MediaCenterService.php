<?php

namespace App\Services\Media;

use Illuminate\Support\Facades\File;

class MediaCenterService
{
    protected string $mediaPath = 'uploads/uploads';

    public function __construct(
        protected UploadService $uploadService,
    ) {
    }

    /**
     * List all files in the media center directory.
     *
     * @return array<string> Filenames sorted reverse-chronologically
     */
    public function listFiles(): array
    {
        $directory = public_path($this->mediaPath);

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);

            return [];
        }

        $files = array_diff(scandir($directory), ['.', '..']);
        rsort($files);

        return array_values($files);
    }

    /**
     * Upload files to the media center.
     *
     * @param  array  $files  Array of UploadedFile instances
     * @return array<string> Uploaded file paths
     */
    public function uploadFiles(array $files): array
    {
        return $this->uploadService->uploadMultiple($files, 'uploads');
    }

    /**
     * Delete a single media file.
     */
    public function deleteFile(string $filename): bool
    {
        return $this->uploadService->delete("{$this->mediaPath}/{$filename}");
    }

    /**
     * Delete multiple records by model and IDs.
     * Used by the multipleDelete dashboard function.
     *
     * @param  class-string  $modelClass
     * @param  array<int>  $ids
     */
    public function bulkDelete(string $modelClass, array $ids): int
    {
        return $modelClass::whereIn('id', $ids)->delete();
    }
}
