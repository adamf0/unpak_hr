<?php
namespace Architecture\Domain\ValueObject;

use Architecture\Domain\Enum\FormatDate;
use Carbon\Carbon;
use Exception;

class Date {
    private $value;
    function __construct($value = null) {
        // if(is_null($value)) throw new Exception("object Date can't be null");
        // if(strtotime($value) == false) throw new Exception("object Date invalid format");
        $this->value = Carbon::parse($value);
    }

    function now(){
        $this->value = Carbon::now();
        return $this;
    }
    function val(){
        return $this->value;
    }
    function toString(){
        return $this->value->toString();
    }
    function toFormat(FormatDate $format){
        return $this->value->settings(['formatFunction' => 'translatedFormat'])->format($format->val());
    }
    function getYear(){
        return $this->value->settings(['formatFunction' => 'translatedFormat'])->format('Y');
    }
    function isGreater(Date $target){
        return $this->value->gt($target->val());
    }
    function isLess(Date $target){
        return $this->value->lt($target->val());
    }
    function differentDays(Date $target){
        return $target->val()->diff($this->value)->days;
    }
    function inRangeDate(Date $start,Date $end){
        return now()->between($start->val(),$end->val());
    }
}