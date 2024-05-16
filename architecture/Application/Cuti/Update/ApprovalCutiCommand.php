<?php

namespace Architecture\Application\Cuti\Update;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Application\Cuti\CutiBase;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class ApprovalCutiCommand extends Command
{
    use IdentityCommand,CutiBase;
    public function __construct($id,$status, public TypeData $option = TypeData::Entity) {
        $this->id = $id;
        $this->status = $status;
    }
}