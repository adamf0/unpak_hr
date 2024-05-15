<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TopNavMenuDropdownChild extends Component
{
    /**
     * Create a new component instance.
     */
    public $icon="bi bi-exclamation-circle";
    public $class=false;
    public function __construct($icon="bi bi-exclamation-circle",$class=false)
    {
        $this->icon = $icon;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.top-nav-menu-dropdown-child',['icon'=>$this->icon,'class'=>$this->class]);
    }
}
