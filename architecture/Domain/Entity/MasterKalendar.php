<?php
namespace Architecture\Domain\Entity;

use Architecture\Application\MasterKalendar\MasterKalendarBase;
use Architecture\Domain\ValueObject\Date;

class MasterKalendar extends BaseEntity{
    use MasterKalendarBase;
    public function __construct($id=null,?Date $tanggal_mulai=null,?Date $tanggal_berakhir=null,$keterangan=null){
        $this->id = $id;
        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_berakhir = $tanggal_berakhir;
    }
}