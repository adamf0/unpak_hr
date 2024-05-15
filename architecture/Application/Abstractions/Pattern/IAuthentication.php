<?php

namespace Architecture\Application\Abstractions\Pattern;

use Architecture\Domain\Entity\Pengguna;

interface IAuthentication
{
    public function Authentication(Pengguna $pengguna);
}