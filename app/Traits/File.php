<?php 

namespace Apps\Traits;

trait File {
    public function makeTempFile($file, $path){

        $originalName = $file->getClientOriginalName();

        $extension = $file->extension();
        
        $name = time() . "." . $extension;

        $tempPath = $path . $name;

        \Storage::disk('local')->put($tempPath . ".temp",  \File::get($file));

        return $tempPath;
    }



    public function storageTempFile($tempPath){

        \Storage::disk('local')->move($tempPath . ".temp", $tempPath);

        return asset($tempPath);
    }  


    public function storageDeleteFile($path){

        \Storage::delete($path);
    }
}