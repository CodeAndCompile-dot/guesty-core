<?php

namespace App\Services\Media;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UploadService
{
    /**
     * Upload a file to the specified folder under public/uploads.
     *
     * @param  UploadedFile  $file  The uploaded file instance
     * @param  string  $folder  Subfolder name under public/uploads (e.g. 'properties', 'blogs')
     * @param  string|null  $existingFile  Path to an existing file to delete after upload
     * @return string The relative path to the uploaded file (e.g. 'uploads/properties/abc123.jpg')
     */
    public function upload(UploadedFile $file, string $folder, ?string $existingFile = null): string
    {
        $destination = public_path("uploads/{$folder}");

        if (! File::isDirectory($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        if ($existingFile) {
            $this->delete($existingFile);
        }

        $filename = $this->generateFilename($file);

        $file->move($destination, $filename);

        return "uploads/{$folder}/{$filename}";
    }

    /**
     * Delete a file from the public directory.
     */
    public function delete(?string $path): bool
    {
        if (! $path) {
            return false;
        }

        $fullPath = public_path($path);

        if (File::exists($fullPath)) {
            return File::delete($fullPath);
        }

        return false;
    }

    /**
     * Upload multiple files to the specified folder.
     *
     * @param  array<UploadedFile>  $files
     * @return array<string> Array of uploaded file paths
     */
    public function uploadMultiple(array $files, string $folder): array
    {
        $paths = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $paths[] = $this->upload($file, $folder);
            }
        }

        return $paths;
    }

    /**
     * Generate a unique filename preserving the original extension.
     */
    protected function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();

        return Str::uuid()->toString().'.'.$extension;
    }
}
