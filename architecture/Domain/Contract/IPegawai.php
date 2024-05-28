<?php
namespace Architecture\Domain\Contract;

use Architecture\Application\Pegawai\PegawaiBase;
use Architecture\Domain\Entity\BaseEntity;

abstract class IPegawai extends BaseEntity{
    use PegawaiBase;
}