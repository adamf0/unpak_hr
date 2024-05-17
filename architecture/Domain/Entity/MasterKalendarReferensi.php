<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IMasterKalendar;

class MasterKalendarReferensi extends IMasterKalendar{
    public static function make($id=null){
        $instance = new self();
        $instance->id = $id;
        return $instance;
    }
}