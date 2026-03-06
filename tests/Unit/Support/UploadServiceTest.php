<?php

namespace Tests\Unit\Support;

use App\Services\Media\UploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class UploadServiceTest extends TestCase
{
    protected UploadService $uploadService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uploadService = new UploadService;
    }

    protected function tearDown(): void
    {
        // Clean up test upload directory
        $testDir = public_path('uploads/test');

        if (File::isDirectory($testDir)) {
            File::deleteDirectory($testDir);
        }

        parent::tearDown();
    }

    public function test_upload_stores_file_and_returns_path(): void
    {
        $file = UploadedFile::fake()->image('photo.jpg', 100, 100);

        $path = $this->uploadService->upload($file, 'test');

        $this->assertStringStartsWith('uploads/test/', $path);
        $this->assertStringEndsWith('.jpg', $path);
        $this->assertFileExists(public_path($path));
    }

    public function test_upload_creates_directory_if_missing(): void
    {
        $dir = public_path('uploads/test_nonexistent');

        if (File::isDirectory($dir)) {
            File::deleteDirectory($dir);
        }

        $file = UploadedFile::fake()->image('photo.png', 50, 50);

        $path = $this->uploadService->upload($file, 'test_nonexistent');

        $this->assertFileExists(public_path($path));

        // Cleanup
        File::deleteDirectory($dir);
    }

    public function test_upload_deletes_existing_file_when_provided(): void
    {
        $first = UploadedFile::fake()->image('first.jpg', 100, 100);
        $firstPath = $this->uploadService->upload($first, 'test');

        $this->assertFileExists(public_path($firstPath));

        $second = UploadedFile::fake()->image('second.jpg', 100, 100);
        $secondPath = $this->uploadService->upload($second, 'test', $firstPath);

        $this->assertFileDoesNotExist(public_path($firstPath));
        $this->assertFileExists(public_path($secondPath));
    }

    public function test_delete_removes_existing_file(): void
    {
        $file = UploadedFile::fake()->image('delete_me.jpg', 50, 50);
        $path = $this->uploadService->upload($file, 'test');

        $this->assertFileExists(public_path($path));

        $result = $this->uploadService->delete($path);

        $this->assertTrue($result);
        $this->assertFileDoesNotExist(public_path($path));
    }

    public function test_delete_returns_false_for_nonexistent_file(): void
    {
        $result = $this->uploadService->delete('uploads/test/nonexistent.jpg');

        $this->assertFalse($result);
    }

    public function test_delete_returns_false_for_null_path(): void
    {
        $result = $this->uploadService->delete(null);

        $this->assertFalse($result);
    }

    public function test_upload_multiple_stores_all_files(): void
    {
        $files = [
            UploadedFile::fake()->image('one.jpg', 50, 50),
            UploadedFile::fake()->image('two.png', 50, 50),
            UploadedFile::fake()->image('three.gif', 50, 50),
        ];

        $paths = $this->uploadService->uploadMultiple($files, 'test');

        $this->assertCount(3, $paths);

        foreach ($paths as $path) {
            $this->assertFileExists(public_path($path));
        }
    }

    public function test_upload_generates_unique_filenames(): void
    {
        $file1 = UploadedFile::fake()->image('same_name.jpg', 50, 50);
        $file2 = UploadedFile::fake()->image('same_name.jpg', 50, 50);

        $path1 = $this->uploadService->upload($file1, 'test');
        $path2 = $this->uploadService->upload($file2, 'test');

        $this->assertNotEquals($path1, $path2);
    }
}
