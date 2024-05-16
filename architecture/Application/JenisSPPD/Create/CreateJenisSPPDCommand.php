<?php

namespace Architecture\Application\JenisSPPD\Create;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Domain\Shared\NamingEntity;
use Architecture\Shared\TypeData;

class CreateJenisSPPDCommand extends Command
{
    use NamingEntity;
    public function __construct(public $nama, public TypeData $option = TypeData::Entity) {}
}