<?php

namespace Architecture\Application\JenisCuti\Create;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Application\JenisCuti\JenisCutiBase;
use Architecture\Shared\TypeData;

class CreateJenisCutiCommand extends Command
{
    use JenisCutiBase;
    public function __construct(public $nama,public $min,public $max,public $dokumen,public $kondisi=null, public TypeData $option = TypeData::Entity) {}
}