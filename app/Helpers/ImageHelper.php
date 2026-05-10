<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Max thumbnail width in pixels.
     */
    const THUMB_WIDTH = 400;

    /**
     * Max thumbnail height in pixels.
     */
    const THUMB_HEIGHT = 300;

    /**
     * JPEG quality for thumbnails (1-100).
     */
    const THUMB_QUALITY = 80;

    /**
     * Compressed image max dimension in pixels (1280px = HD).
     * Both width and height are capped at this value (aspect-ratio preserved).
     */
    const COMPRESS_MAX_DIM = 1280;

    /**
     * File size threshold below which compression is skipped (bytes).
     * Images already smaller than this are stored as-is.
     */
    const COMPRESS_SKIP_BYTES = 307200; // 300 KB

    /**
     * JPEG quality for compressed images (1-100).
     */
    const COMPRESS_QUALITY = 82;

    /**
     * Get the thumbnail path for a given original image path.
     * Generates the thumbnail on-the-fly if it doesn't exist yet.
     *
     * @param string $originalPath  e.g. "assets/abc123.jpg"
     * @return string               e.g. "assets/thumbs/abc123.jpg"
     */
    public static function thumbnail(string $originalPath): string
    {
        $thumbPath = self::thumbPath($originalPath);

        // If thumbnail already exists, return it
        if (Storage::disk('public')->exists($thumbPath)) {
            return $thumbPath;
        }

        // If original doesn't exist either, fall back to original path
        if (!Storage::disk('public')->exists($originalPath)) {
            return $originalPath;
        }

        // Generate thumbnail
        if (self::generateThumbnail($originalPath, $thumbPath)) {
            return $thumbPath;
        }

        // Fallback to original if generation failed
        return $originalPath;
    }

    /**
     * Build the thumbnail path from the original path.
     * e.g. "assets/abc123.jpg" → "assets/thumbs/abc123.jpg"
     */
    public static function thumbPath(string $originalPath): string
    {
        $dir = dirname($originalPath);
        $filename = basename($originalPath);

        return $dir . '/thumbs/' . $filename;
    }

    /**
     * Generate a thumbnail for the given image and save it.
     *
     * @param string $originalPath  Path relative to public disk
     * @param string|null $thumbPath  Target thumbnail path (auto-computed if null)
     * @return bool
     */
    public static function generateThumbnail(string $originalPath, ?string $thumbPath = null): bool
    {
        $thumbPath = $thumbPath ?? self::thumbPath($originalPath);

        try {
            $fullOriginal = Storage::disk('public')->path($originalPath);

            if (!file_exists($fullOriginal)) {
                return false;
            }

            // Detect image type
            $info = @getimagesize($fullOriginal);
            if (!$info) {
                return false;
            }

            $mime = $info['mime'];
            $origWidth = $info[0];
            $origHeight = $info[1];

            // Create source image resource based on MIME type
            $source = match ($mime) {
                'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($fullOriginal),
                'image/png' => @imagecreatefrompng($fullOriginal),
                'image/webp' => @imagecreatefromwebp($fullOriginal),
                'image/gif' => @imagecreatefromgif($fullOriginal),
                default => false,
            };

            if (!$source) {
                return false;
            }

            // Calculate new dimensions maintaining aspect ratio
            $ratio = min(
                self::THUMB_WIDTH / $origWidth,
                self::THUMB_HEIGHT / $origHeight
            );

            // Don't upscale — only downscale
            if ($ratio >= 1) {
                imagedestroy($source);
                // Image is already small enough, just copy it
                Storage::disk('public')->copy($originalPath, $thumbPath);
                return true;
            }

            $newWidth = (int) round($origWidth * $ratio);
            $newHeight = (int) round($origHeight * $ratio);

            // Create thumbnail canvas
            $thumb = imagecreatetruecolor($newWidth, $newHeight);

            // Handle transparency for PNG/WebP
            if ($mime === 'image/png' || $mime === 'image/webp') {
                imagealphablending($thumb, false);
                imagesavealpha($thumb, true);
                $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
                imagefilledrectangle($thumb, 0, 0, $newWidth, $newHeight, $transparent);
            }

            // Resize with high-quality resampling
            imagecopyresampled(
                $thumb, $source,
                0, 0, 0, 0,
                $newWidth, $newHeight,
                $origWidth, $origHeight
            );

            // Ensure the thumbs directory exists
            $fullThumbPath = Storage::disk('public')->path($thumbPath);
            $thumbDir = dirname($fullThumbPath);
            if (!is_dir($thumbDir)) {
                mkdir($thumbDir, 0755, true);
            }

            // Save thumbnail in the same format as original
            $success = match ($mime) {
                'image/jpeg', 'image/jpg' => imagejpeg($thumb, $fullThumbPath, self::THUMB_QUALITY),
                'image/png' => imagepng($thumb, $fullThumbPath, 8), // compression level 0-9
                'image/webp' => imagewebp($thumb, $fullThumbPath, self::THUMB_QUALITY),
                'image/gif' => imagegif($thumb, $fullThumbPath),
                default => false,
            };

            // Free memory
            imagedestroy($source);
            imagedestroy($thumb);

            return $success;

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Thumbnail generation failed: ' . $e->getMessage(), [
                'original' => $originalPath,
                'thumb' => $thumbPath,
            ]);
            return false;
        }
    }

    /**
     * Compress/resize image to 854x480 (480p) during upload.
     * This replaces the original file with the compressed version.
     *
     * @param string $originalPath  Path relative to public disk
     * @return bool
     */
    public static function compressImage(string $originalPath): bool
    {
        try {
            $fullPath = Storage::disk('public')->path($originalPath);

            if (!file_exists($fullPath)) {
                return false;
            }

            // Skip compression for already-small files
            if (filesize($fullPath) <= self::COMPRESS_SKIP_BYTES) {
                return true;
            }

            // Detect image type
            $info = @getimagesize($fullPath);
            if (!$info) {
                return false;
            }

            $mime = $info['mime'];
            $origWidth = $info[0];
            $origHeight = $info[1];

            // If image is already within max dimension, skip resize
            if ($origWidth <= self::COMPRESS_MAX_DIM && $origHeight <= self::COMPRESS_MAX_DIM) {
                // Re-encode at quality to reduce file size without resizing
                // Only worthwhile for JPEG/WebP
                if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
                    $source = @imagecreatefromjpeg($fullPath);
                    if ($source) {
                        imagejpeg($source, $fullPath, self::COMPRESS_QUALITY);
                        imagedestroy($source);
                    }
                } elseif ($mime === 'image/webp') {
                    $source = @imagecreatefromwebp($fullPath);
                    if ($source) {
                        imagewebp($source, $fullPath, self::COMPRESS_QUALITY);
                        imagedestroy($source);
                    }
                }
                return true;
            }

            // Create source image resource based on MIME type
            $source = match ($mime) {
                'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($fullPath),
                'image/png'              => @imagecreatefrompng($fullPath),
                'image/webp'             => @imagecreatefromwebp($fullPath),
                'image/gif'              => @imagecreatefromgif($fullPath),
                default                  => false,
            };

            if (!$source) {
                return false;
            }

            // Calculate proportional dimensions (no letterbox / padding)
            $ratio = min(
                self::COMPRESS_MAX_DIM / $origWidth,
                self::COMPRESS_MAX_DIM / $origHeight
            );

            $newWidth  = (int) round($origWidth  * $ratio);
            $newHeight = (int) round($origHeight * $ratio);

            // Create canvas sized exactly to the new proportional dimensions
            $compressed = imagecreatetruecolor($newWidth, $newHeight);

            // Preserve transparency for PNG/WebP
            if ($mime === 'image/png' || $mime === 'image/webp') {
                imagealphablending($compressed, false);
                imagesavealpha($compressed, true);
                $transparent = imagecolorallocatealpha($compressed, 0, 0, 0, 127);
                imagefilledrectangle($compressed, 0, 0, $newWidth, $newHeight, $transparent);
            }

            // High-quality resize
            imagecopyresampled(
                $compressed, $source,
                0, 0, 0, 0,
                $newWidth, $newHeight,
                $origWidth, $origHeight
            );

            // Save compressed image, replacing the original
            $success = match ($mime) {
                'image/jpeg', 'image/jpg' => imagejpeg($compressed, $fullPath, self::COMPRESS_QUALITY),
                'image/png'              => imagepng($compressed, $fullPath, 7),
                'image/webp'             => imagewebp($compressed, $fullPath, self::COMPRESS_QUALITY),
                'image/gif'              => imagegif($compressed, $fullPath),
                default                  => false,
            };

            // Free memory
            imagedestroy($source);
            imagedestroy($compressed);

            return $success;

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Image compression failed: ' . $e->getMessage(), [
                'path' => $originalPath,
            ]);
            return false;
        }
    }
}

