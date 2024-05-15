<?php
namespace Architecture\Shared\Behavioral;

class MappingHtmlOrmContext{
    public $states = [];
    public function __construct(public $stopExecuteState = true){}

    public function addState(RuleRenderHtmlStartegy $startegy, MappingHtmlOrmStateInterface $state) {
        $this->states[] = ["html"=>$state,"rule"=>$startegy];
    }
    public function handleState($clearState = false, $debug=false) {
        $output = null;
        foreach ($this->states as $state) {
            if($debug) dump($state['rule'],$state['rule']->rule());
        
            if($state['rule']->rule() && $this->stopExecuteState){
                $output = $state['html']->handle()->GetOutput();
                break;
            }
            if($state['rule']->rule() && !$this->stopExecuteState){
                $output .= $state['html']->handle()->GetOutput();
            }
        }
        if($clearState) $this->states = [];
        
        return $output;
    }
    public function handleStateWithFlex($clearState = false, $debug=false) {
        $output = "<div class='row'>";
        foreach ($this->states as $state) {
            if($debug) dump($state['rule'],$state['rule']->rule());

            $hasFlex     = isset($state['html']->withFlex) && $state['html']->withFlex;
            $content     = $state['html']->handle()->GetOutput();
            if($state['rule']->rule() && $this->stopExecuteState){
                $output .= $hasFlex? $content:"<div class='col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 col-xxl-3 d-flex justify-content-center mt-2'>{$content}</div>";
                break;
            }
            if($state['rule']->rule() && !$this->stopExecuteState){
                $output .= $hasFlex? $content:"<div class='col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 col-xxl-3 d-flex justify-content-center mt-2'>{$content}</div>";
            }
        }
        $output .= "</div>";
        if($clearState) $this->states = [];
        
        return $output;
    }
}