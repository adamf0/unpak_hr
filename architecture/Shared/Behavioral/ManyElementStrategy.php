<?php
namespace Architecture\Shared\Behavioral;

class ManyElementStrategy implements ConjunctionStartegy {
    public function build(array $list){
        $lastItem = array_pop($list);
        return implode(', ', $list) . ' dan ' . $lastItem;
    }
}