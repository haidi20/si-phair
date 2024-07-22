<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ImageHasParent extends Model
{
    use HasFactory;

    protected $appends = [
        "image_review",
    ];

    public function getImageReviewAttribute()
    {
        // $filePath = storage_path($this->pathName);
        $path = str_replace("app/public/", "", $this->path_name);
        $filePath = Storage::disk('public')->exists($path);

        // Check if the file exists
        if ($filePath) {
            // Read the file contents and encode it to base64
            $base64Data = Storage::disk('public')->get($path);
            return "data:image/{$this->format};base64," . base64_encode($base64Data);
        }

        // Return null if the file does not exist
        return $filePath;
    }
}
