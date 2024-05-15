<?php
namespace Architecture\Shared\Behavioral;

class OneElementStrategy implements ConjunctionStartegy {
    public function build(array $list){
        return $list[0];
    }
}