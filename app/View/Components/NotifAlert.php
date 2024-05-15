<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NotifAlert extends Component
{
    /**
     * Create a new component instance.
     */
    public $message = false;
    public $class = false;
    public function __construct($message=false,$class=false)
    {
        $this->message = $message;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return ($this->message && $this->class? view('components.notif-alert',["message"=>$this->message,"class"=>$this->class]):"");
    }
}
