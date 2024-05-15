<?php
namespace Architecture\Shared\Behavioral;

class BadgeAsMember extends MappingHtmlOrmStateInterface{
    public function __construct(public $item){}
    
    public function handle() {
        $this->output = "
        <br>
        <span data-bs-toggle='tooltip' title='' data-bs-original-title='Sebagai Anggota " . $this->item->GetDosen()->GetNama() . " - " . $this->item->GetDosen()->GetNidn() . "'>
            <span class='badge rounded-pill bg-success'>Sebagai Anggota</span>
        </span>
        ";

        return $this;
    }
}