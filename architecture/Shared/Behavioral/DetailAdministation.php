<?php
namespace Architecture\Shared\Behavioral;

class DetailAdministation extends MappingHtmlOrmStateInterface{
    public function __construct(public $type="fakultas"){}
    
    public function handle() {
        $this->output .= '<br><a href="#" class="badge bg-light text-black btn-detail-administrasi" data-type="'.$this->type.'" style="text-decoration: none;">Lihat Data</a>';
        return $this;
    }
}