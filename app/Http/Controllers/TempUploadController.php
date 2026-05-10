<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TempUploadController extends Controller
{
    /**
     * Accept a single image file, store it in temp/, generate thumbnail,
     * and return the paths as JSON so the frontend can track them.
     *
     * POST /assets/upload-photo
     */
    public function upload(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        try {
            $file = $request->file('photo');

            // Generate unique filename to avoid collisions
            $extension = $file->getClientOriginalExtension() ?: 'jpg';
            $filename  = Str::uuid() . '.' . strtolower($extension);
            $tempPath  = 'temp/' . $filename;

            // Store in public disk under storage/app/public/temp/
            Storage::disk('public')->putFileAs('temp', $file, $filename);

            // Compress in-place (proportional, max 1280px, skip < 300 KB)
            ImageHelper::compressImage($tempPath);

            // Generate thumbnail for preview on listing pages
            ImageHelper::generateThumbnail($tempPath);

            return response()->json([
                'success'    => true,
                'path'       => $tempPath,
                'thumb_path' => ImageHelper::thumbPath($tempPath),
                'url'        => Storage::disk('public')->url($tempPath),
                'thumb_url'  => Storage::disk('public')->url(ImageHelper::thumbPath($tempPath)),
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload gagal: ' . $e->getMessage(),
            ], 422);
        }
    }
}
