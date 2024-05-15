<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SidebarItemMenuDropdown extends Component
{
    /**
     * Create a new component instance.
     */
    public $active=false;
    public $parent="y";
    public $target="x";
    public $class=false;
    public $icon="bi bi-menu-button-wide";
    public $title="#";

    public function __construct($title="#",$parent="y",$target="x",$class=false,$icon="bi bi-menu-button-wide",$active=false)
    {
        $this->parent   = $parent;
        $this->target   = $target;
        $this->class    = $class;
        $this->icon     = $icon;
        $this->title    = $title;
        $this->active   = $active;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar-item-menu-dropdown',[
            'parent'    => $this->parent,
            'target'    => $this->target,
            'class'     => $this->class,
            'icon'      => $this->icon,
            'title'     => $this->title,
            'active'    => $this->active,
        ]);
    }
}
