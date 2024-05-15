<?php
namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadFile{

    function upload(UploadedFile $file, $path)
    {
        return Storage::disk('public')->put($path, $file);
    }
}
