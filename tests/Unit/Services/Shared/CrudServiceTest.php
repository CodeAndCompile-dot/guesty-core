<?php

namespace Tests\Unit\Services\Shared;

use App\Repositories\Contracts\BaseRepositoryInterface;
use App\Services\Media\UploadService;
use App\Services\Shared\CrudService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Mockery;
use Tests\TestCase;

class CrudServiceTest extends TestCase
{
    protected CrudService $service;

    protected BaseRepositoryInterface $repository;

    protected UploadService $uploadService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = Mockery::mock(BaseRepositoryInterface::class);
        $this->uploadService = Mockery::mock(UploadService::class);

        $this->service = new CrudService($this->repository, $this->uploadService);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_all_delegates_to_repository(): void
    {
        $expected = new Collection([]);

        $this->repository
            ->shouldReceive('all')
            ->once()
            ->with(['*'])
            ->andReturn($expected);

        $result = $this->service->all();

        $this->assertSame($expected, $result);
    }

    public function test_find_delegates_to_repository(): void
    {
        $model = Mockery::mock(Model::class);

        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn($model);

        $result = $this->service->find(1);

        $this->assertSame($model, $result);
    }

    public function test_store_creates_record_without_images(): void
    {
        $request = Request::create('/test', 'POST');
        $data = ['name' => 'Test', 'description' => 'A test'];
        $model = Mockery::mock(Model::class);

        $this->repository
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($model);

        $result = $this->service->store($request, $data);

        $this->assertSame($model, $result);
    }

    public function test_store_processes_image_uploads(): void
    {
        $file = UploadedFile::fake()->image('photo.jpg');
        $request = Request::create('/test', 'POST', [], [], ['image' => $file]);
        $data = ['name' => 'Test'];

        $this->uploadService
            ->shouldReceive('upload')
            ->once()
            ->andReturn('uploads/test/photo.jpg');

        $model = Mockery::mock(Model::class);

        $this->repository
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) {
                return $arg['name'] === 'Test' && $arg['image'] === 'uploads/test/photo.jpg';
            }))
            ->andReturn($model);

        $result = $this->service->store($request, $data, ['image' => 'test']);

        $this->assertSame($model, $result);
    }

    public function test_update_modifies_record(): void
    {
        $request = Request::create('/test', 'PUT');
        $data = ['name' => 'Updated'];

        $this->repository
            ->shouldReceive('update')
            ->once()
            ->with(1, $data)
            ->andReturn(true);

        $result = $this->service->update(1, $request, $data);

        $this->assertTrue($result);
    }

    public function test_destroy_deletes_record(): void
    {
        $this->repository
            ->shouldReceive('delete')
            ->once()
            ->with(1)
            ->andReturn(true);

        $result = $this->service->destroy(1);

        $this->assertTrue($result);
    }

    public function test_destroy_deletes_associated_images(): void
    {
        $model = Mockery::mock(Model::class)->makePartial();
        $model->shouldReceive('getAttribute')
            ->with('image')
            ->andReturn('uploads/test/photo.jpg');
        $model->shouldReceive('offsetExists')
            ->with('image')
            ->andReturn(true);

        $this->repository
            ->shouldReceive('findOrFail')
            ->once()
            ->with(1)
            ->andReturn($model);

        $this->uploadService
            ->shouldReceive('delete')
            ->once()
            ->with('uploads/test/photo.jpg')
            ->andReturn(true);

        $this->repository
            ->shouldReceive('delete')
            ->once()
            ->with(1)
            ->andReturn(true);

        $result = $this->service->destroy(1, ['image']);

        $this->assertTrue($result);
    }

    public function test_activate_delegates_to_repository(): void
    {
        $this->repository
            ->shouldReceive('activate')
            ->once()
            ->with(5)
            ->andReturn(true);

        $result = $this->service->activate(5);

        $this->assertTrue($result);
    }

    public function test_deactivate_delegates_to_repository(): void
    {
        $this->repository
            ->shouldReceive('deactivate')
            ->once()
            ->with(5)
            ->andReturn(true);

        $result = $this->service->deactivate(5);

        $this->assertTrue($result);
    }

    public function test_duplicate_delegates_to_repository(): void
    {
        $model = Mockery::mock(Model::class);

        $this->repository
            ->shouldReceive('duplicate')
            ->once()
            ->with(3)
            ->andReturn($model);

        $result = $this->service->duplicate(3);

        $this->assertSame($model, $result);
    }
}
