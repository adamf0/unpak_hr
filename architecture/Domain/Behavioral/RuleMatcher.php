<?php
namespace Architecture\Domain\Behavioral;

interface RuleMatcher {
    public function match($value, $operator, $target);
}