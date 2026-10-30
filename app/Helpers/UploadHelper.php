<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class UploadHelper
{
    /**
     * Upload berkas ke public/uploads/{folder} dengan format penamaan YmdHis-{file}
     * Sesuai standar tugas: format YmdHis-{file}
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string Path relatif untuk akses URL (contoh: /uploads/logos/20261020093000-nama-file.jpg)
     */
    public static function upload(UploadedFile $file, string $folder = 'general'): string
    {
        $destinationPath = public_path("uploads/{$folder}");
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $timestamp = date('YmdHis');
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $cleanName = Str::slug($originalName) ?: 'file';
        $extension = $file->getClientOriginalExtension() ?: 'bin';

        // Format nama berkas: YmdHis-{file}
        $filename = "{$timestamp}-{$cleanName}.{$extension}";

        $file->move($destinationPath, $filename);

        return "/uploads/{$folder}/{$filename}";
    }
}
