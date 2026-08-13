<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicProductImage
{
    public const PUBLIC_DIRECTORY = '/images/products/';

    public function store(UploadedFile $file): string
    {
        $directory = config('uploads.product_images_path');
        File::ensureDirectoryExists($directory);

        $extension = strtolower($file->guessExtension() ?: $file->extension() ?: 'jpg');
        $filename = Str::uuid().'.'.$extension;
        $file->move($directory, $filename);

        return self::PUBLIC_DIRECTORY.$filename;
    }

    public function delete(?string $image): void
    {
        if (! $image) {
            return;
        }

        if (str_starts_with($image, self::PUBLIC_DIRECTORY)) {
            $path = rtrim(config('uploads.product_images_path'), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.basename($image);

            if (File::isFile($path)) {
                File::delete($path);
            }

            return;
        }

        if (str_starts_with($image, '/storage/')) {
            Storage::disk('public')->delete(Str::after($image, '/storage/'));
        }
    }
}
