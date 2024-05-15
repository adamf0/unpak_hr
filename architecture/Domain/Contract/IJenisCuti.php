<?php
namespace Architecture\Domain\Contract;

use Architecture\Application\JenisCuti\JenisCutiBase;
use Architecture\Domain\Entity\BaseEntity;

abstract class IJenisCuti extends BaseEntity{
    use JenisCutiBase;
}