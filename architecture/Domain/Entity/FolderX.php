<?php
namespace Architecture\Domain\Entity;

class FolderX
{
    public function __construct(private $type = "path", private $value = ""){}

    public static function FromPath($value){
        return new self($value);
    }
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
    public function getValue()
    {
        return $this->value;
    }
}