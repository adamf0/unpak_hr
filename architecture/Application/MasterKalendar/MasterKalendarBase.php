<?php

namespace Architecture\Application\MasterKalendar;

use Architecture\Domain\ValueObject\Date;

trait MasterKalendarBase 
{
    public ?Date $tanggal_mulai;
    public ?Date $tanggal_berakhir;
    public $keterangan=null;

    public function GetTanggalMulai(){
        return $this->tanggal_mulai;
    }
    public function GetTanggalAkhir(){
        return $this->tanggal_berakhir;
    }
    public function GetKeterangan(){
        return $this->keterangan;
    }
}