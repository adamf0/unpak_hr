<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Img extends Component
{
    /**
     * Create a new component instance.
     */
    public $path;
    public $alt=false;
    public $class=false;
    public $id=false;
    public $error;
    public $islazy=false;

    public function __construct($path,$class=false,$id=false,$alt=false,$error=false,$islazy=false)
    {
        $this->path = $path;
        $this->class = $class;
        $this->id = $id;
        $this->alt = $alt;
        $this->error = $error;
        $this->islazy = $islazy;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.img',[
            'path' => $this->path,
            'class' => $this->class,
            'id' => $this->id,
            'alt' => $this->alt,
            'error' => $this->error,
            'islazy' => $this->islazy,
        ]);
    }
}
