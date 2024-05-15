<?php

namespace Architecture\Application\Auth\LogOut;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Illuminate\Support\Facades\Session;

class LogOutCommandHandler extends CommandHandler
{
    public function handle(LogOutCommand $command)
    {
        Session::flush();
    }
}