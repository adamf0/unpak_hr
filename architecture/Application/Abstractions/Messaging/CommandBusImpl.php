<?php

namespace Architecture\Application\Abstractions\Messaging;

use Illuminate\Bus\Dispatcher;

class CommandBusImpl implements ICommandBus
{

    public function __construct(
        protected Dispatcher $bus,
        //interface log
    ) {}


    public function dispatch(Command $command): mixed
    {
        //log::exec(method,param)
        return $this->bus->dispatch($command);
    }

    public function register(array $map): void
    {
        $this->bus->map($map);
    }
}