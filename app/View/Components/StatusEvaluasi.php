<?php

namespace App\View\Components;

use Closure;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatusEvaluasi extends Component
{
    public $source=null;
    public $target=null;
    public $text=null;

    public function __construct($source=null,$target=null,$text=null)
    {
        if($source==null) throw new Exception('source in component StatusEvaluasi is required');
        if(empty($target)) throw new Exception('target in component StatusEvaluasi is empty');
        if(empty($text)) throw new Exception('text in component StatusEvaluasi is required');

        $this->source = $source;
        $this->target = $target;
        $this->text = $text;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.status-evaluasi',[
            'source'=>$this->source,
            'target'=>$this->target,
            'text'=>$this->text,
        ]);
    }
}
