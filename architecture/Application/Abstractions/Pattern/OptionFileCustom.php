<?php

namespace Architecture\Application\Abstractions\Pattern;

class OptionFileCustom extends IOptionFile{

    public function getFileLocation()
    {
        return $this->newFileName;
    }
}