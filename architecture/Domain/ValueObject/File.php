<?php
namespace Architecture\Domain\ValueObject;

use Exception;
use Illuminate\Support\Facades\Storage;
use Architecture\Shared\Facades\Utility;

class File {
    private $fileName;
    private $url;
    private $extension;
    private $available=false;
    
    function __construct($fileName = null, $disk = null) {
        if(is_null($fileName)) throw new Exception("object fileName can't be null");
        if(is_null($disk)) throw new Exception("object disk can't be null");
        
        $this->fileName = $fileName;
        $this->available = Storage::disk($disk)->exists($fileName);
        $this->url = Utility::loadAsset("$disk/$fileName");
        $this->extension = pathinfo($fileName, PATHINFO_EXTENSION);
    }

    function getFileName(){
        return $this->fileName;
    }
    function getUrl(){
        return $this->url;
    }
    function isAvailable(){
        return $this->available;
    }
    function getExtension(){
        return $this->extension;
    }
}