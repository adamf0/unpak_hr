<?php

namespace Architecture\Application\JenisIzin\Update;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Domain\Shared\NamingEntity;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class UpdateJenisIzinCommand extends Command
{
    use IdentityCommand,NamingEntity;
    public function __construct(public $id,public $nama, public TypeData $option = TypeData::Entity) {}
}