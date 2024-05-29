<?php
namespace Architecture\Domain\Contract;

use Architecture\Application\KlaimAbsen\KlaimAbsenBase;
use Architecture\Domain\Entity\BaseEntity;

abstract class IKlaimAbsen extends BaseEntity{
    use KlaimAbsenBase;
}