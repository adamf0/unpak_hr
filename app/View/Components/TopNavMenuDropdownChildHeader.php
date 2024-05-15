<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TopNavMenuDropdownChildHeader extends Component
{
    /**
     * Create a new component instance.
     */
    public $title="#";
    public $link="#";
    public $desc="#";
    public function __construct($title="#",$link="#",$desc="#")
    {
        $this->title = $title;
        $this->link = $link;
        $this->desc = $desc;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.top-nav-menu-dropdown-child-header',[
            'title'=>$this->title,
            'link'=>$this->link,
            'desc'=>$this->desc,
        ]);
    }
}
