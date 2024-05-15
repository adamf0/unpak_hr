<?php

namespace App\View\Components;

use Closure;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class IndikatorGroup extends Component
{
    /**
     * Create a new component instance.
     */
    public $group;
    public $title;
    public $collapse = false;
    public function __construct($group, $title, $collapse=false)
    {
        if($group==null) throw new Exception('group in component indikator group cannot be null');
        if($title==null) throw new Exception('title in component indikator title cannot be null');

        $this->group = $group;
        $this->title = $title;
        $this->collapse = $collapse;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.indikator-group',[
            'group'=>$this->group,
            'title'=>$this->title,
            'collapse'=>$this->collapse,
        ]);
    }
}
