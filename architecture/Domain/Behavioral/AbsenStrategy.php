<?php
namespace Architecture\Domain\Behavioral;
use Architecture\Shared\Facades\Utility;

class AbsenStrategy implements IAbsenStrategy {
    public function getBackground($klaim, $dataAbsen, $tanggal, $now) {
        return "#198754"; // masuk
    }

    public function getTitle($klaim, $dataAbsen, $tanggal, $now) {
        $label = match(true){
            !empty($klaim->jam_masuk) || !empty($klaim->jam_keluar) => "(klaim)",
            !empty($dataAbsen->catatan_pulang) => "(Pulang Cepat)",
            Utility::isLate($dataAbsen->absen_masuk, $dataAbsen->tanggal) => "(Telat)",
            default => ""
        };
        if(is_null($dataAbsen?->absen_keluar)){
            dd($dataAbsen,$klaim);
        }
        return sprintf(
            "%s - %s %s",
            date('H:i:s', strtotime(empty($dataAbsen->absen_masuk) ? $klaim->jam_masuk : $dataAbsen->absen_masuk)),
            date('H:i:s', strtotime(empty($dataAbsen->absen_keluar) ? $klaim->jam_keluar : $dataAbsen->absen_keluar)),
            $label
        );
    }
}