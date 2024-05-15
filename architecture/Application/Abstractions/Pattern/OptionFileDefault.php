<?php

namespace Architecture\Application\Abstractions\Pattern;

use Exception;

class OptionFileDefault extends IOptionFile{

    public function getFileLocation()
    {
        if(!is_null($this->newFileName)) throw new Exception("invalid argument in implement contract IOptionFile");
        return $this->file->getClientOriginalName();
    }
}