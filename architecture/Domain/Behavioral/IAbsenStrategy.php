<?php
namespace Architecture\Domain\Behavioral;

interface IAbsenStrategy {
    public function getBackground($klaim, $dataAbsen, $tanggal, $now);
    public function getTitle($klaim, $dataAbsen, $tanggal, $now);
}
