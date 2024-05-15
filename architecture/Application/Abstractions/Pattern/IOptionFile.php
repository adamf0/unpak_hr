<?php

namespace Architecture\Application\Abstractions\Pattern;

use Illuminate\Http\UploadedFile;

abstract class IOptionFile
{
    public function __construct(protected UploadedFile $file, protected $diskName, protected $newFileName=null){}

    function getFile(){
        return $this->file;
    }
    abstract function getFileLocation();
    function getDisk(){
        // if(!Storage::disk()->exists($this->diskName)) throw new Exception("disk $this->diskName can't be found in configuration");
        return $this->diskName;
    }
}