<?php

namespace App\View\Components;

use Closure;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputDate extends Component
{
    /**
     * Create a new component instance.
     */
    public $title=false;
    public $name;
    public $default=false;
    public $class='';
    public $yearOnly = false;
    public $orientation = 'top';
    public $disable = false;
    public function __construct($title=false,$name,$default=false,$class='',$yearOnly = false, $disable=false, $orientation = 'top')
    {
        // if($title==null) throw new Exception('title in component indicator cannot be null'); 
        if($name==null) throw new Exception('name in component indicator cannot be null'); 

        $this->title = $title;
        $this->name = $name;
        $this->default = $default;
        $this->class = $class;
        $this->yearOnly = $yearOnly;
        $this->disable = $disable;
        $this->orientation = $orientation;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.input-date',[
            'title'=>$this->title,
            'name'=>$this->name,
            'default'=>$this->default,
            'class'=>$this->class,
            'isyear'=>$this->yearOnly,
            'disable'=>$this->disable,
            'orientation'=>$this->orientation,
        ]);
    }
}
