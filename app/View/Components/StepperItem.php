<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StepperItem extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        private $key=0,
        private $isActive=false,
        private $isDisable=true,
        private $numberStep=null,
        private $isEndStep=true,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.stepper_item',[
            "key" => $this->key,
            "isActive" => $this->isActive,
            "isDisable" => $this->isDisable,
            "numberStep" => $this->numberStep,
            "isEndStep" => $this->isEndStep,
        ]);
    }
}
