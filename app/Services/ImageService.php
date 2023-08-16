<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use PSpell\Config;

class ImageService
{

    private $driver;

    public function __construct()
    {
        $this->driver = Config::get('filesystems.default');

    }

    public function upload($request, $path_to_upload = '/', $previous_file_name = null, $driver){
        // get driver name
        $driver = $driver ?? $this->driver;

        // delete previous image if exists
        if ($previous_file_name) {
            if (Storage::disk($this->driver)->exists($path_to_upload .'/'. $previous_file_name)) {
                Storage::disk($this->driver)->delete($path_to_upload .'/'. $previous_file_name);
            }
        }

        // upload file using storage facade
        Storage::disk($this->driver)->put($path_to_upload .'/'. $filename, $file_to_upload);

        // check if file is being uploaded
        if(Storage::disk($this->driver)->exists($path_to_upload .'/'. $filename)){
            return $filename;
        }

        return null;
    }
}
