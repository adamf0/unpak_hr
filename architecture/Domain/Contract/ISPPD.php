<?php
namespace Architecture\Domain\Contract;

use Architecture\Application\SPPD\SPPDBase;
use Architecture\Domain\Entity\BaseEntity;

abstract class ISPPD extends BaseEntity{
    use SPPDBase;
}