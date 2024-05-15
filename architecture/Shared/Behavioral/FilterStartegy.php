<?php
namespace Architecture\Shared\Behavioral;

interface FilterStartegy{
    public function onFilter($row);
}