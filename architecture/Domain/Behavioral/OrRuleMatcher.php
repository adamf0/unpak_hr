<?php
namespace Architecture\Domain\Behavioral;

class OrRuleMatcher implements RuleMatcher {
    public function match($value, $operator, $target) {
        $result = collect([]);
        foreach($value as $v){
            $result->push($v==$target);
        }
        return $result->filter(fn($r)=>$r==true)->values()->count()>0;
    }
}