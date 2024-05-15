<?php
namespace Architecture\Shared\Behavioral;

use Architecture\Domain\Enum\TypeStatusAnggota;

class StatusWaitingAsMember extends MappingHtmlOrmStateInterface{
    public function __construct(public $item, public ?TypeStatusAnggota $statusVerifikasi = null){}
    
    public function handle() {
        
        $this->output = match($this->statusVerifikasi){
            TypeStatusAnggota::Diterima => "<br><span class='badge rounded-pill bg-success'>Terima</span>",
            TypeStatusAnggota::Ditolak  => "<br><span class='badge rounded-pill bg-danger'>Tolak</span>",
            default                     => "<br><span class='badge rounded-pill bg-warning text-black'>Menunggu</span>",
        };

        return $this;
    }
}