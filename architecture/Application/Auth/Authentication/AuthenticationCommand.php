<?php

namespace Architecture\Application\Auth\Authentication;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Shared\TypeData;

class AuthenticationCommand extends Command
{

    public function __construct(
        public $username,
        public $password,
        public TypeData $option = TypeData::Entity
    ) {}

    public function getUsername(): string
    {
        return $this->username;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function getOption()
    {
        return $this->option;
    }
}