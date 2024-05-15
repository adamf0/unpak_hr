<?php
namespace Architecture\Domain\Behavioral;

class AndRuleMatcher implements RuleMatcher {
    public function match($value, $operator, $target) {
        return $value==$target;
    }
}