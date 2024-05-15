<?php
namespace Architecture\Shared\Behavioral;

abstract class MessageHandler
{
    public $status;
    public $message;
    
    public function getMessage(){
        return $this->message;
    }
}