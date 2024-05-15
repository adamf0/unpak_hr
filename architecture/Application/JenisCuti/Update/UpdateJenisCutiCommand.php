<?php

namespace Architecture\Application\JenisCuti\Update;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Application\JenisCuti\JenisCutiBase;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class UpdateJenisCutiCommand extends Command
{
    use IdentityCommand,JenisCutiBase;
    public function __construct(public $id,public $nama,public $min,public $max,public $dokumen,public $kondisi, public TypeData $option = TypeData::Entity) {}
}