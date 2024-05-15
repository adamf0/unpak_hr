<?php

namespace Architecture\Application\JenisIzin\Create;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Domain\Shared\NamingEntity;
use Architecture\Shared\TypeData;

class CreateJenisIzinCommand extends Command
{
    use NamingEntity;
    public function __construct(public $nama, public TypeData $option = TypeData::Entity) {}
}