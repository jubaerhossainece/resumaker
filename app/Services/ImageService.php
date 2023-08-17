<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    protected $driver;

    public function __construct()
    {
        $this->driver = config('filesystems.default');
    }


    public function upload($file, $path_to_upload = '/', $previous_file_name = null, $driver = null){
        // get driver name
        $driver = $driver ?? $this->driver;

        // delete previous image if exists
        if ($previous_file_name) {
            if (Storage::disk($driver)->exists($path_to_upload .'/'. $previous_file_name)) {
                Storage::disk($driver)->delete($path_to_upload .'/'. $previous_file_name);
            }
        }

        $filename = Str::random(10).time().'.'.$file->getClientOriginalExtension();

        // upload file using storage facade
        Storage::disk($driver)->putFileAs($path_to_upload, $file, $filename);

        // check if file is being uploaded
        if(Storage::disk($driver)->exists($path_to_upload .'/'. $filename)){
            return $filename;
        }

        return null;
    }


    public static function getUrl($path, $file_name, $disk = 'public'){
        return Storage::disk($disk)->url($path.'/' . $file_name);
    }
}
