<?php
namespace Architecture\Domain\Contract;

use Architecture\Application\MasterKalendar\MasterKalendarBase;
use Architecture\Domain\Entity\BaseEntity;

abstract class IMasterKalendar extends BaseEntity{
    use MasterKalendarBase;
}