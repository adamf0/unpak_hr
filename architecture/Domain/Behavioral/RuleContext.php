<?php
namespace Architecture\Domain\Behavioral;

class RuleContext {
    private $strategy;

    public function setStrategy(RuleMatcher $strategy) {
        $this->strategy = $strategy;
    }

    public function matchRule($value, $operator, $target) {
        return $this->strategy->match($value, $operator, $target);
    }
}