<?php

namespace Architecture\Application\JenisSPPD\Update;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Domain\Shared\NamingEntity;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class UpdateJenisSPPDCommand extends Command
{
    use IdentityCommand,NamingEntity;
    public function __construct(public $id,public $nama, public TypeData $option = TypeData::Entity) {}
}