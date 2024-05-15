<?php

namespace Architecture\External\Port;

use Architecture\Application\Abstractions\Pattern\IOptionFile;
use Exception;
use Illuminate\Support\Facades\Storage;

class FileSystem //dibuat facades karena ada fitur baru untuk read file dalam disk
{
    public function __construct(private ?IOptionFile $option=null){
        if(is_null($this->option)) throw new Exception("contract IOptionFile can't be null");
        return $this;
    }

    public function storeFileReturnFileLocation(){
        if(is_null($this->option->getFileLocation())) throw new Exception("file can't be null");
        $this->option->getFileLocation()->storeAs('/', $this->option->getFileLocation(), ['disk' => $this->option->getDisk()]);

        return $this->option->getFileLocation();
    }
    public function storeFileWithReplaceFileAndReturnFileLocation(){
        if(is_null($this->option->getFileLocation())) throw new Exception("file can't be null");
        if(Storage::disk($this->option->getDisk())->exists($this->option->getFileLocation())){
            Storage::disk($this->option->getDisk())->delete($this->option->getFileLocation());
        }
        $this->option->getFile()->storeAs('/', $this->option->getFileLocation(), ['disk' => $this->option->getDisk()]);

        return $this->option->getFileLocation();
    }
}
