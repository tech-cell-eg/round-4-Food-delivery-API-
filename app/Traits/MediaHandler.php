<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait MediaHandler
{
    public function storeImage(UploadedFile $image, string $folder): string
    {
        $uniqueName = time() . '_' . Str::random(20) . '.' . $image->getClientOriginalExtension();

        return $image->storeAs($folder, $uniqueName, 'public');
    }

    public function deleteImage(string $imagePath): void
    {
        Storage::disk('public')->delete($imagePath);
    }

}
