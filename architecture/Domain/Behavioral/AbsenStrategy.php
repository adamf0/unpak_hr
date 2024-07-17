<?php
namespace Architecture\Domain\Behavioral;
use Architecture\Shared\Facades\Utility;
use Carbon\Carbon;

class AbsenStrategy implements IAbsenStrategy {
    public function getBackground($klaim, $dataAbsen, $tanggal, $now) {
        $warna = match(true){
            !empty($klaim?->jam_masuk) || !empty($klaim?->jam_keluar) => "#198754",
            !empty($dataAbsen?->catatan_pulang) || !Utility::is8Hour($dataAbsen->tanggal, $dataAbsen->absen_masuk, $dataAbsen->absen_keluar) => "#808080",
            Utility::isLate($dataAbsen?->absen_masuk, $dataAbsen?->tanggal) => "#000",
            default => "#198754"
        };

        return $warna; // masuk
    }

    public function getTitle($klaim, $dataAbsen, $tanggal, $now) {
        $aturanPulang = "14:59:00";
        if (Carbon::now()->setTimezone('Asia/Jakarta')->dayOfWeek == Carbon::FRIDAY) {
            $aturanPulang = "13:59:00";
        } elseif (Carbon::now()->setTimezone('Asia/Jakarta')->dayOfWeek == Carbon::SATURDAY) {
            $aturanPulang = "11:59:00";
        }

        $jam_masuk = is_null($dataAbsen?->absen_masuk) ? $klaim?->jam_masuk : $dataAbsen?->absen_masuk;
        $jam_keluar = is_null($dataAbsen?->absen_keluar) ? $klaim?->jam_keluar : $dataAbsen?->absen_keluar;
        $pulangCepat = is_null($jam_keluar)? false:strtotime($dataAbsen?->tanggal." ".$jam_keluar)<=strtotime($dataAbsen?->tanggal." ".$aturanPulang);
        // /dump($dataAbsen?->tanggal." ".$jam_keluar, $dataAbsen?->tanggal." ".$aturanPulang);

        $lebel = "";
        if(!empty($klaim?->jam_masuk) || !empty($klaim?->jam_keluar)){
            $label = "(klaim)";
        } else if(!empty($dataAbsen?->catatan_pulang) || !Utility::is8Hour($dataAbsen->tanggal, $dataAbsen->absen_masuk, $dataAbsen->absen_keluar)){
            $label = "(Pulang Cepat)";
        } else if(Utility::isLate($dataAbsen?->absen_masuk, $dataAbsen?->tanggal)){
            $label = "(Telat)";
        }
        
        $jam_masuk = is_null($dataAbsen?->absen_masuk) ? $klaim?->jam_masuk : $dataAbsen?->absen_masuk;
        $jam_keluar = is_null($dataAbsen?->absen_keluar) ? $klaim?->jam_keluar : $dataAbsen?->absen_keluar;
        return sprintf(
            "%s - %s %s",
            is_null($jam_masuk)? "-":date('H:i:s', strtotime($jam_masuk)),
            is_null($jam_keluar)? "-":date('H:i:s', strtotime($jam_keluar)),
            $label
        );
    }
}