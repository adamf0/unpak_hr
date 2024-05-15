<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IVideoKegiatan;

class VideoKegiatanEntitas extends IVideoKegiatan{
    public static function make($id=null,$nama=null,$nilai=null){
        $instance = new self();
        $instance->id = $id;
        $instance->nama = $nama;
        $instance->nilai = $nilai;
        return $instance;
    }
}