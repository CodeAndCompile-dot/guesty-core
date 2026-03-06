<?php

namespace App\Services\Content;

use App\Services\Media\UploadService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Shared service for SeoCms and LandingCms — both have identical
 * JSON section assembly (attraction_secion, video_section) and
 * vacation image upload patterns.
 */
class SeoContentService
{
    protected array $imageFields = [
        'image' => 'cms',
        'bannerImage' => 'cms',
        'vacation_four_image' => 'cms',
        'vacation_three_image' => 'cms',
        'vacation_two_image' => 'cms',
        'vacation_one_image' => 'cms',
    ];

    public function __construct(
        protected string $modelClass,
        protected UploadService $uploadService,
    ) {
    }

    /**
     * Get all records ordered by ID desc.
     */
    public function all()
    {
        return $this->modelClass::orderBy('id', 'desc')->get();
    }

    /**
     * Find a record by ID.
     */
    public function find(int|string $id): ?Model
    {
        return $this->modelClass::find($id);
    }

    /**
     * Store a new record with image uploads and JSON section assembly.
     */
    public function store(Request $request, array $data): Model
    {
        $data = $this->processImageUploads($request, $data);
        $data = $this->assembleAttractionSection($request, $data);
        $data = $this->assembleVideoSection($request, $data);

        return $this->modelClass::create($data);
    }

    /**
     * Update an existing record with image uploads and JSON section assembly.
     */
    public function update(int|string $id, Request $request, array $data): bool
    {
        $existing = $this->modelClass::findOrFail($id);
        $data = $this->processImageUploads($request, $data, $existing);
        $data = $this->assembleAttractionSection($request, $data, $existing);
        $data = $this->assembleVideoSection($request, $data);

        return $existing->update($data);
    }

    /**
     * Delete a record.
     */
    public function destroy(int|string $id): bool
    {
        $record = $this->modelClass::findOrFail($id);

        return (bool) $record->delete();
    }

    /**
     * Process standard image uploads.
     */
    protected function processImageUploads(Request $request, array $data, ?Model $existing = null): array
    {
        foreach ($this->imageFields as $field => $folder) {
            if ($request->hasFile($field)) {
                $oldFile = $existing?->{$field} ?? null;
                $data[$field] = $this->uploadService->upload(
                    $request->file($field),
                    $folder,
                    $oldFile,
                );
            }
        }

        return $data;
    }

    /**
     * Assemble the attraction_secion JSON from repeating form fields.
     *
     * Legacy pattern: attraction_heading[], attraction_title[], attraction_image[], attraction_content[]
     * On update, attraction_image_hidden[] preserves existing images when no new file uploaded.
     */
    protected function assembleAttractionSection(Request $request, array $data, ?Model $existing = null): array
    {
        $headings = $request->input('attraction_heading', []);

        if (empty($headings)) {
            return $data;
        }

        $sections = [];
        $existingSections = [];

        if ($existing && $existing->attraction_secion) {
            $existingSections = json_decode($existing->attraction_secion, true) ?? [];
        }

        foreach ($headings as $key => $heading) {
            $section = [
                'attraction_heading' => $heading,
                'attraction_title' => $request->input("attraction_title.{$key}", ''),
                'attraction_content' => $request->input("attraction_content.{$key}", ''),
            ];

            // Handle image upload for this section item
            if ($request->hasFile("attraction_image.{$key}")) {
                $section['attraction_image'] = $this->uploadService->upload(
                    $request->file("attraction_image.{$key}"),
                    'cms',
                );
            } elseif ($request->input("attraction_image_hidden.{$key}")) {
                $section['attraction_image'] = $request->input("attraction_image_hidden.{$key}");
            } elseif (isset($existingSections[$key]['attraction_image'])) {
                $section['attraction_image'] = $existingSections[$key]['attraction_image'];
            } else {
                $section['attraction_image'] = '';
            }

            $sections[] = $section;
        }

        $data['attraction_secion'] = json_encode($sections);

        return $data;
    }

    /**
     * Assemble the video_section JSON from repeating form fields.
     *
     * Legacy pattern: video_heading[], video_link[], video_content[]
     */
    protected function assembleVideoSection(Request $request, array $data): array
    {
        $headings = $request->input('video_heading', []);

        if (empty($headings)) {
            return $data;
        }

        $sections = [];

        foreach ($headings as $key => $heading) {
            $sections[] = [
                'video_heading' => $heading,
                'video_link' => $request->input("video_link.{$key}", ''),
                'video_content' => $request->input("video_content.{$key}", ''),
            ];
        }

        $data['video_section'] = json_encode($sections);

        return $data;
    }
}
