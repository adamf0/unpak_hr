<?php
namespace Architecture\Shared\Creational;

use Architecture\Shared\Export;
use Architecture\Shared\Stream;

class FileManager
{
    public static function ExportFile(Export $callback){
        return $callback->Export();
    }
    public static function StreamFile(Stream $callback){
        return $callback->Stream();
    }
}
