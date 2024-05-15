<?php
namespace Architecture\Domain\Behavioral;

use Exception;

class RangeRuleMatcher implements RuleMatcher {
    public function match($value, $operator, $target) {
        if( 
            array_key_exists("faculty",$value) && 
            array_key_exists("faculty",$target) &&  
            array_key_exists($target["faculty"],$value["faculty"]) && 
            isset($value["faculty"][$target["faculty"]])
        ){
            $faculty = $value["faculty"][$target["faculty"]];
            if(
                array_key_exists("programStudy",$target) && 
                array_key_exists("valueSinta",$target) && 
                isset($target["valueSinta"])
            ){
                $programStudy = array_key_exists("programStudy", $faculty) && 
                                array_key_exists("programStudy", $target) && 
                                array_key_exists($target["programStudy"],$faculty["programStudy"]) && 
                                isset($faculty["programStudy"][$target["programStudy"]]) 
                                ? $faculty["programStudy"][$target["programStudy"]] : $faculty["programStudy"]["default"];
                
                if( isset($programStudy["range"]["min"]) && isset($programStudy["range"]["max"]) ){
                    $rangeMinValue = $programStudy["range"]["min"]["value"];
                    $rangeMinOperator = $programStudy["range"]["min"]["operator"];
                    $rangeMaxValue = $programStudy["range"]["max"]["value"];
                    $rangeMaxOperator = $programStudy["range"]["max"]["operator"];
                    $value = $target["valueSinta"];
                    
                    return $this->toComparison($value,$rangeMinOperator,$rangeMinValue) && $this->toComparison($value,$rangeMaxOperator,$rangeMaxValue);
                } else if( isset($programStudy["range"]["min"]) && !isset($programStudy["range"]["max"])  ){
                    $rangeMinValue = $programStudy["range"]["min"]["value"];
                    $rangeMinOperator = $programStudy["range"]["min"]["operator"];
                    $value = $target["valueSinta"];
    
                    return $this->toComparison($value,$rangeMinOperator,$rangeMinValue);
                } else if( !isset($programStudy["range"]["min"]) && isset($programStudy["range"]["max"])  ){
                    $rangeMaxValue = $programStudy["range"]["max"]["value"];
                    $rangeMaxOperator = $programStudy["range"]["max"]["operator"];
                    $value = $target["valueSinta"];
                    return $value>0 && $this->toComparison($value,$rangeMaxOperator,$rangeMaxValue);
                }
            } else if(array_key_exists("range",$faculty)){
                if( isset($faculty["range"]["min"]) && isset($faculty["range"]["max"]) ){
                    $rangeMinValue = $faculty["range"]["min"]["value"];
                    $rangeMinOperator = $faculty["range"]["min"]["operator"];
                    $rangeMaxValue = $faculty["range"]["max"]["value"];
                    $rangeMaxOperator = $faculty["range"]["max"]["operator"];
                    $value = $target["valueSinta"];
                    
                    return $this->toComparison($value,$rangeMinOperator,$rangeMinValue) && $this->toComparison($value,$rangeMaxOperator,$rangeMaxValue);
                } else if( isset($faculty["range"]["min"]) && !isset($faculty["range"]["max"])  ){
                    $rangeMinValue = $faculty["range"]["min"]["value"];
                    $rangeMinOperator = $faculty["range"]["min"]["operator"];
                    $value = $target["valueSinta"];
    
                    return $this->toComparison($value,$rangeMinOperator,$rangeMinValue);
                } else if( !isset($faculty["range"]["min"]) && isset($faculty["range"]["max"])  ){
                    $rangeMaxValue = $faculty["range"]["max"]["value"];
                    $rangeMaxOperator = $faculty["range"]["max"]["operator"];
                    $value = $target["valueSinta"];
                    return $value>0 && $this->toComparison($value,$rangeMaxOperator,$rangeMaxValue);
                }
            } else{
                throw new Exception("RangeRuleMatcher is broken");
            }
        } else{
            return $value["faculty"]["default"]["value"];
        }
    }

    function toComparison($value,$operator,$target){
        return match($operator){
            ">"=>$value>$target,
            ">="=>$value>=$target,
            "=="=>$value==$target,
            "!="=>$value!=$target,
            "<"=>$value<$target,
            "<="=>$value<=$target,
            default=>throw new Exception("invalid operator comparison")
        };
    }
}