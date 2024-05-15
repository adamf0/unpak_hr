<?php
namespace Architecture\Shared\Behavioral;

class TwoElementStrategy implements ConjunctionStartegy {
    public function build(array $list){
        return implode(' dan ', $list);
    }
}