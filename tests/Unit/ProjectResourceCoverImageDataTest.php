<?php

namespace Tests\Unit;

use App\Filament\Resources\ProjectResource;
use Tests\TestCase;

class ProjectResourceCoverImageDataTest extends TestCase
{
    public function test_uploaded_cover_image_path_overrides_cover_image(): void
    {
        $data = [
            'title' => 'Test Project',
            'cover_image' => 'https://example.com/old.jpg',
            'cover_image_upload' => 'projects/covers/new-upload.jpg',
        ];

        $normalized = ProjectResource::normalizeCoverImageData($data);

        $this->assertSame('projects/covers/new-upload.jpg', $normalized['cover_image']);
        $this->assertArrayNotHasKey('cover_image_upload', $normalized);
    }

    public function test_cover_image_remains_when_upload_is_empty(): void
    {
        $data = [
            'title' => 'Test Project',
            'cover_image' => 'https://example.com/kept.jpg',
            'cover_image_upload' => null,
        ];

        $normalized = ProjectResource::normalizeCoverImageData($data);

        $this->assertSame('https://example.com/kept.jpg', $normalized['cover_image']);
        $this->assertArrayNotHasKey('cover_image_upload', $normalized);
    }
}
