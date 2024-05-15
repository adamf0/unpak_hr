<?php
namespace Architecture\Domain\Behavioral;

class InRuleMatcher implements RuleMatcher {
    public function match($value, $operator, $target) {
        return in_array($target, $value["value"]);
    }
}