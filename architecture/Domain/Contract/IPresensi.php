<?php
namespace Architecture\Domain\Contract;

use Architecture\Application\Presensi\PresensiBase;
use Architecture\Domain\Entity\BaseEntity;

abstract class IPresensi extends BaseEntity{
    use PresensiBase;
}