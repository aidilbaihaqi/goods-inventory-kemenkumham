<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizer
{
    private ImageManager $manager;
    
    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Optimize and store uploaded image
     */
    public function optimizeAndStore(UploadedFile $file, string $directory = 'items'): string
    {
        // Validate file
        $this->validateImage($file);
        
        // Generate unique filename
        $filename = Str::uuid() . '.webp';
        $path = $directory . '/' . $filename;
        
        // Process image
        $image = $this->manager->read($file->getPathname());
        
        // Resize if too large (max 800x800)
        if ($image->width() > 800 || $image->height() > 800) {
            $image->scale(width: 800, height: 800);
        }
        
        // Convert to WebP with quality optimization
        $optimizedImage = $image->toWebp(quality: 85);
        
        // Store to public disk
        Storage::disk('public')->put($path, $optimizedImage);
        
        return $path;
    }

    /**
     * Validate uploaded image
     */
    private function validateImage(UploadedFile $file): void
    {
        // Check file size (max 5MB)
        if ($file->getSize() > 5 * 1024 * 1024) {
            throw new \InvalidArgumentException('File size must be less than 5MB');
        }
        
        // Check file type
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \InvalidArgumentException('File must be an image (JPEG, PNG, GIF, WebP)');
        }
        
        // Check image dimensions (min 100x100, max 4000x4000)
        $imageInfo = getimagesize($file->getPathname());
        if ($imageInfo === false) {
            throw new \InvalidArgumentException('Invalid image file');
        }
        
        [$width, $height] = $imageInfo;
        
        if ($width < 100 || $height < 100) {
            throw new \InvalidArgumentException('Image dimensions must be at least 100x100 pixels');
        }
        
        if ($width > 4000 || $height > 4000) {
            throw new \InvalidArgumentException('Image dimensions must not exceed 4000x4000 pixels');
        }
    }

    /**
     * Delete image from storage
     */
    public function deleteImage(?string $path): bool
    {
        if (!$path) {
            return true;
        }
        
        return Storage::disk('public')->delete($path);
    }
}