<?php
namespace Architecture\Application\Abstractions\Messaging;

use Architecture\Shared\TypeData;

abstract class BaseCommandQuery
{
    public TypeData $option = TypeData::Entity;
    public function getOption()
    {
        return $this->option;
    }    
}