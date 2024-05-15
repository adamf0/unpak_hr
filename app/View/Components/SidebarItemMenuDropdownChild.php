<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SidebarItemMenuDropdownChild extends Component
{
    /**
     * Create a new component instance.
     */
    public $active=false;
    public $link="#";
    public $icon="bi bi-circle";
    public $class=false;
    public $title="#";
    public function __construct($title="#",$link="#",$icon="bi bi-circle",$class=false,$active=false)
    {
        $this->link = $link;
        $this->icon = $icon;
        $this->class = $class;
        $this->title = $title;
        $this->active = $active;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar-item-menu-dropdown-child',[
            'link' => $this->link,
            'icon' => $this->icon,
            'class' => $this->class,
            'title' => $this->title,
            'active' => $this->active,
        ]);
    }
}
