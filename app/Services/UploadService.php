<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadService
{
    public static function uploadDokumen(
        $file,
        $folder,
        $prefix = 'file'
    ) {
        $extension = $file->getClientOriginalExtension();

        $filename =
            $prefix . '_' .
            time() . '_' .
            Str::random(5) . '.' .
            $extension;

        return $file->storeAs(
            $folder,
            $filename,
            'public'
        );
    }

    public static function deleteFile($path)
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}