<?php

namespace Architecture\Application\Pengguna\Create;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Domain\Enum\TypeRole;
use Architecture\Domain\Shared\NamingEntity;
use Architecture\Shared\TypeData;

class CreatePenggunaCommand extends Command
{
    use NamingEntity;
    public function __construct(public $username, public $password=null, public $nama, public ?TypeRole $level=null, public TypeData $option = TypeData::Entity) {}

    public function GetUsername(){
        return $this->username;
    }
    public function GetPassword(){
        return $this->password;
    }
    public function GetLevel(){
        return $this->level;
    }
}