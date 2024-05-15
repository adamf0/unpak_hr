<?php
namespace Architecture\Shared\Behavioral;

class SuccessMessageHandler extends MessageHandler
{
    public function __construct($message){
        $this->status   = true;
        $this->message  = $message;
    }
}