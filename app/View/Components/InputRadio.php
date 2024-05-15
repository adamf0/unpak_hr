<?php

namespace App\View\Components;

use Closure;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputRadio extends Component
{
    /**
     * Create a new component instance.
     */
    public $name;
    public $id=null;
    public $value;
    public $selected=false;
    public $isend=false;
    public $disable=false;
    public function __construct($name,$id=null,$value=null,$selected,$isend=false,$disable=false)
    {
        if($name==null) throw new Exception('name in component indicator cannot be null'); 
        if($id==null) throw new Exception('id in component indicator cannot be null'); 

        $this->name = $name;
        $this->id = $id;
        $this->value = $value;
        $this->selected = $selected;
        $this->isend = $isend;
        $this->disable = $disable;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.input-radio',[
            'name'=>$this->name,
            'id'=>$this->id,
            'value'=>$this->value,
            'selected'=>$this->selected,
            'isend'=>$this->isend,
            'disable'=>$this->disable,
        ]);
    }
}
