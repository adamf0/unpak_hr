<?php
namespace Architecture\Shared\Creational;

use Architecture\Shared\Export;
use Architecture\Shared\MergeFile;
use Architecture\Shared\Stream;
use Illuminate\Http\UploadedFile;

class FileManager
{
    public static function ExportFile(Export $callback){
        return $callback->Export();
    }
    public static function StreamFile(Stream $callback){
        return $callback->Stream();
    }
    public static function MergeFile(MergeFile $callback, UploadedFile $file){
        return $callback->Merge($file);
    }
}
