<?php
namespace Architecture\Domain\Behavioral;

class AbsenContext {
    private $strategy;

    public function __construct(AbsenStrategy $strategy) {
        $this->strategy = $strategy;
    }

    public function setStrategy(AbsenStrategy $strategy) {
        $this->strategy = $strategy;
    }

    public function getBackground($klaim, $dataAsben, $tanggal, $now) {
        return $this->strategy->getBackground($klaim, $dataAsben, $tanggal, $now);
    }

    public function getTitle($klaim, $dataAsben, $tanggal, $now) {
        return $this->strategy->getTitle($klaim, $dataAsben, $tanggal, $now);
    }
}
