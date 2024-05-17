<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IMasterKalendar;
use Architecture\Domain\ValueObject\Date;

class MasterKalendarEntitas extends IMasterKalendar{
    public static function make($id=null,?Date $tanggal_mulai=null,?Date $tanggal_berakhir=null,$keterangan=null){
        $instance = new self();
        $instance->id = $id;
        $instance->tanggal_mulai = $tanggal_mulai;
        $instance->tanggal_berakhir = $tanggal_berakhir;
        
        return $instance;
    }
}