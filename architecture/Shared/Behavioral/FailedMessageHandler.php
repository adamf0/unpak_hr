<?php
namespace Architecture\Shared\Behavioral;

class FailedMessageHandler extends MessageHandler
{
    public function __construct($message){
        $this->status   = false;
        $this->message  = $message;
    }
}