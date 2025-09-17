<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class OptimizeImageUpload
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info('OptimizeImageUpload middleware called for ' . $request->method() . ' ' . $request->path());
        
        // Only process POST/PUT requests with file uploads
        if (($request->isMethod('post') || $request->isMethod('put')) && $request->hasFile('cover')) {
            Log::info('Image optimization middleware triggered for ' . $request->method() . ' request');
            $this->optimizeImage($request);
        }

        return $next($request);
    }

    /**
     * Optimize the uploaded image
     *
     * @param Request $request
     * @return void
     */
    private function optimizeImage(Request $request)
    {
        $file = $request->file('cover');
        
        if (!$file || !$file->isValid()) {
            Log::warning('Invalid file provided to image optimization middleware');
            return;
        }

        try {
            Log::info('Starting image optimization for file: ' . $file->getClientOriginalName());
            
            // Create image manager with GD driver
            $manager = new ImageManager(new Driver());
            
            // Read the image
            $image = $manager->read($file->getPathname());
            
            // Get original dimensions
            $width = $image->width();
            $height = $image->height();
            
            Log::info("Original image dimensions: {$width}x{$height}");
            
            // Calculate new dimensions (max 500px while maintaining aspect ratio)
            $maxSize = 500;
            $shouldResize = false;
            $newWidth = $width;
            $newHeight = $height;
            
            if ($width > $maxSize || $height > $maxSize) {
                $shouldResize = true;
                if ($width > $height) {
                    $newWidth = $maxSize;
                    $newHeight = (int) ($height * $maxSize / $width);
                } else {
                    $newHeight = $maxSize;
                    $newWidth = (int) ($width * $maxSize / $height);
                }
                
                Log::info("Resizing image to: {$newWidth}x{$newHeight}");
                
                // Resize the image
                $image->resize($newWidth, $newHeight);
            }
            
            // Always optimize quality (85% for good quality with smaller file size)
            $image->toJpeg(85);
            
            // Save the optimized image to a temporary location
            $tempPath = tempnam(sys_get_temp_dir(), 'optimized_') . '.jpg';
            $image->save($tempPath);
            
            Log::info("Optimized image saved to: {$tempPath}");
            
            // Create a new UploadedFile with the optimized image
            $optimizedFile = new \Illuminate\Http\UploadedFile(
                $tempPath,
                pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.jpg',
                'image/jpeg',
                null,
                true // mark as test to avoid moving the file
            );
            
            // Replace the original file with the optimized one
            $request->files->set('cover', $optimizedFile);
            
            Log::info('Image optimization completed successfully');
            
        } catch (\Exception $e) {
            // Log the error but don't break the upload process
            Log::error('Image optimization failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}