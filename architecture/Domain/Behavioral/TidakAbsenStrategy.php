<?php
namespace Architecture\Domain\Behavioral;

class TidakAbsenStrategy implements IAbsenStrategy {
    public function getBackground($klaim, $dataAbsen, $tanggal, $now) {
        return "#dc3545"; // tidak masuk
    }

    public function getTitle($klaim, $dataAbsen, $tanggal, $now) {
        return "tidak masuk";
    }
}