<?php

namespace Architecture\Application\JenisSPPD\Delete;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class DeleteJenisSPPDCommand extends Command
{
    use IdentityCommand;
    public function __construct(public $id, public TypeData $option = TypeData::Entity) {}
}