<?php

namespace Architecture\Application\Pengguna\Delete;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class DeletePenggunaCommand extends Command
{
    use IdentityCommand;
    public function __construct(public $id, public TypeData $option = TypeData::Entity) {}
}