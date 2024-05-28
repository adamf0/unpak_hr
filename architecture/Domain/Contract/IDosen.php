<?php
namespace Architecture\Domain\Contract;

use Architecture\Application\Dosen\DosenBase;
use Architecture\Domain\Entity\BaseEntity;

abstract class IDosen extends BaseEntity{
    use DosenBase;
}