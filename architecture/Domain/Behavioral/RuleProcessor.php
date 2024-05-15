<?php
namespace Architecture\Domain\Behavioral;

use Exception;

class RuleProcessor {
    private $context;

    public function __construct() {
        $this->context = new RuleContext();
    }

    public function checkRule($rules, $input) {
        try {
            if(!array_key_exists('rules',$rules)) throw new Exception("rule statement is not found");

            $results = collect([]);

            foreach ($rules['rules'] as $key => $rule) {
                if (!array_key_exists($key,$input)) {
                    continue;
                }

                $this->context->setStrategy(match(true){
                    $rule["operator"]=="in" => new InRuleMatcher(),
                    $rule["operator"]=="or" && is_array($rule["value"]) => new OrRuleMatcher(),
                    $rule["operator"]=="and" && is_string($rule["value"]) => new AndRuleMatcher(),
                    $rule["operator"]=="range" => new RangeRuleMatcher(),
                    default => throw new Exception("invalid operator")

                });
                $statement = $this->context->matchRule($rule, $rule["operator"], $input[$key]);
                $results->push($statement);
            }

            return $results->filter(fn($r) => $r == true)->values()->count() == count($rules);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
