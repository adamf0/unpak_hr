<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SidebarItemMenu extends Component
{
    /**
     * Create a new component instance.
     */
    public $active=false;
    public $id=false;
    public $link=false;
    public $class=false;
    public $title="";
    public $icon="bi bi-menu-button-wide";
    public $show=true;
    public function __construct($title="#",$link="#",$icon="bi bi-menu-button-wide",$class=false,$id=false,$active=false,$show=true)
    {
        $this->title = $title;
        $this->link = $link;
        $this->class = $class;
        $this->id = $id;
        $this->active = $active;
        $this->icon = $icon;
        $this->show = $show;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar-item-menu',[
            'title' => $this->title,
            'link' => $this->link,
            'class' => $this->class,
            'id' => $this->id,
            'active' => $this->active,
            'icon' => $this->icon,
            'show' => $this->show,
        ]);
    }
}
