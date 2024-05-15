<?php
namespace Architecture\Shared\Behavioral;

class ResultPointSubstansiInternal extends MappingHtmlOrmStateInterface{
    public function __construct(public $item){}
    
    public function handle() {
        $total      = null;
        foreach($this->item->GetListSubstansi() as $substansi){
            if(is_null($substansi->GetPoint())) continue;
            $total += $substansi->GetPoint();
        }
        if(is_null($total))
            $this->output .= null;
        else if($total==0 || $this->item->GetListSubstansi()->count()==0) 
            $this->output .= "0 point";
        else 
            $this->output .= $total/$this->item->GetListSubstansi()->count()." point";

        return $this;
    }
}