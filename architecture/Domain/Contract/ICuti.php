<?php
namespace Architecture\Domain\Contract;

use Architecture\Application\Cuti\CutiBase;
use Architecture\Domain\Entity\BaseEntity;

abstract class ICuti extends BaseEntity{
    use CutiBase;
}