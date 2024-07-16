<?php
namespace Architecture\Domain\Behavioral;
use Architecture\Shared\Facades\Utility;

class DefaultStrategy implements IAbsenStrategy {
    public function getBackground($klaim, $dataAbsen, $tanggal, $now) {
        return "#000"; // default
    }

    public function getTitle($klaim, $dataAbsen, $tanggal, $now) {
        $label = match(true){
            !empty($dataAbsen?->catatan_pulang) => "(Pulang Cepat)",
            Utility::isLate($dataAbsen?->absen_masuk, $dataAbsen?->tanggal) => "(Telat)",
            default => ""
        };

        return sprintf(
            "%s - %s %s",
            is_null($dataAbsen?->absen_masuk) ? "-" : date('H:i:s', strtotime($dataAbsen?->absen_masuk)),
            is_null($dataAbsen?->absen_keluar) ? "-" : date('H:i:s', strtotime($dataAbsen?->absen_keluar)),
            $label
        );
    }
}