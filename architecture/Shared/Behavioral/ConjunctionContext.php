<?php
namespace Architecture\Shared\Behavioral;

class ConjunctionContext {
    public static function merge(ConjunctionStartegy $strategy, array $list){
        return $strategy->build($list);
    }
}