<?php
namespace Architecture\Shared\Behavioral;

use Architecture\Shared\Facades\Utility;

class PointRevision extends MappingHtmlOrmStateInterface{
    public function __construct(public $listItem, public $except = [], public $mapping = [], public ?RuleRenderHtmlStartegy $condition=null){}
    
    public function handle() {
        if(!is_null($this->listItem)){
            $this->output = Utility::getAttributClass($this->listItem,$this->except,$this->mapping);
            if(!is_null($this->condition)){
                $this->output->filter(fn($data)=>$this->condition->rule($data->value))->values();
            }
            $this->output->map(function ($row) {
                $row->value = $row->value->val();
                return $row;
            });
        } else{
            $this->output = collect([]);
        }

        return $this;
    }
}