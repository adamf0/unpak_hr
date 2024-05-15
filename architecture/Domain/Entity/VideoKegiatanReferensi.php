<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IVideoKegiatan;

class VideoKegiatanReferensi extends IVideoKegiatan{
    public static function make($id=null){
        $instance = new self();
        $instance->id = $id;
        return $instance;
    }
}