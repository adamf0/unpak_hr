<?php

namespace App\View\Components;

use Closure;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputSelect extends Component
{
    /**
     * Create a new component instance.
     */
    public $title=false;
    public $name;
    public $class='';
    public $disable = false;
    public function __construct($title=false,$name,$class='',$disable = false)
    {
        // if($title==null) throw new Exception('title in component indicator cannot be null'); 
        if($name==null) throw new Exception('name in component indicator cannot be null'); 

        $this->title = $title;
        $this->name = $name;
        $this->class = $class;
        $this->disable = $disable;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.input-select',[
            'title'=>$this->title,
            'name'=>$this->name,
            'class'=>$this->class,
            'disable'=>$this->disable
        ]);
    }
}
