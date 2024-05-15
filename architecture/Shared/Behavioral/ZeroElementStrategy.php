<?php
namespace Architecture\Shared\Behavioral;

class ZeroElementStrategy implements ConjunctionStartegy {
    public function build(array $list){
        return null;
    }
}