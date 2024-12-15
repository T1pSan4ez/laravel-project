<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    public function upload(UploadedFile $file, string $directory = 'avatars'): string
    {
        $filePath = $file->store($directory, 'public');

        return $filePath;
    }

    public function delete(string $path = null): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
