<?php
namespace Architecture\Shared\Behavioral;

class ButtonButtonAddReviewInternalState extends MappingHtmlOrmStateInterface{
    public function __construct(public $item,public $dataKey){}
    
    public function handle() {
        $this->output = '<a href="#" '.$this->dataKey.'="'.$this->item->GetId().'" class="btn btn-info btn-add-reviewer"><i class="bi bi-person-add"></i></a>';

        return $this;
    }
}