<?php

namespace Architecture\Application\Abstractions\Messaging;

interface IQueryBus
{

    public function ask(Query $query): mixed;

    public function register(array $map): void;

}