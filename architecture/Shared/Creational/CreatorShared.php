<?php
namespace Architecture\Shared\Creational;

use Architecture\Shared\Behavioral\FilterStartegy;
use Illuminate\Support\Collection;

class CreatorShared{
    public static function filterCollect(Collection $list, FilterStartegy $startegy){
        return $list->filter(fn($row)=>$startegy->onFilter($row))->values();   
    }
}