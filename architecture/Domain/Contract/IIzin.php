<?php
namespace Architecture\Domain\Contract;

use Architecture\Application\Izin\IzinBase;
use Architecture\Domain\Entity\BaseEntity;

abstract class IIzin extends BaseEntity{
    use IzinBase;
}