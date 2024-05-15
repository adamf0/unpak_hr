<?php

namespace Architecture\Application\Abstractions\Messaging;

interface ICommandBus
{
    public function dispatch(Command $command): mixed;

    public function register(array $map): void;
}