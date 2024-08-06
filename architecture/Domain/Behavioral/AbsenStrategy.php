<?php
namespace Architecture\Domain\Behavioral;
use Architecture\Shared\Facades\Utility;
use Carbon\Carbon;

class AbsenStrategy implements IAbsenStrategy {
    public function getBackground($klaim, $dataAbsen, $tanggal, $now) {
        $warna = "#198754";
        $jam_masuk = $klaim?->jam_masuk??$dataAbsen?->absen_masuk;
        $jam_keluar = $klaim?->jam_keluar??$dataAbsen?->absen_keluar;
        
        if(!empty($jam_masuk) && !empty($jam_keluar)){
            if( 
                !Utility::isLate($jam_masuk, $dataAbsen?->tanggal) &&
                Utility::is8Hour($dataAbsen->tanggal, $jam_masuk, $jam_keluar)
            ){
                $warna = "#198754";
            } else if( 
                Utility::isLate($jam_masuk, $dataAbsen?->tanggal) &&
                Utility::is8Hour($dataAbsen->tanggal, $jam_masuk, $jam_keluar)
            ){
                $warna = "#198754";
            }
            if( 
                Utility::isLate($jam_masuk, $dataAbsen?->tanggal) &&
                !Utility::is8Hour($dataAbsen->tanggal, $jam_masuk, $jam_keluar)
            ){
                $warna = "#000";
            } else if( 
                !Utility::isLate($jam_masuk, $dataAbsen?->tanggal) &&
                !Utility::is8Hour($dataAbsen->tanggal, $jam_masuk, $jam_keluar)
            ){
                $warna = "#000";
            }
        } else{
            $warna = !Utility::isLate($jam_masuk, $dataAbsen?->tanggal)? "#198754":"#000";
        }

        return $warna; // masuk
    }

    public function getTitle($klaim, $dataAbsen, $tanggal, $now) {
        $jam_masuk = is_null($dataAbsen?->absen_masuk) ? $klaim?->jam_masuk : $dataAbsen?->absen_masuk;
        $jam_keluar = is_null($dataAbsen?->absen_keluar) ? $klaim?->jam_keluar : $dataAbsen?->absen_keluar;
        
        $label = "";
        if(!empty($jam_masuk) && !empty($jam_keluar)){
            if(!empty($klaim?->jam_masuk) || !empty($klaim?->jam_keluar)){
                $label = "(KLAIM)";
            } else if( 
                !Utility::isLate($jam_masuk, $dataAbsen?->tanggal) &&
                Utility::is8Hour($dataAbsen->tanggal, $jam_masuk, $jam_keluar)
            ){
                $label = "";
            } else if( 
                Utility::isLate($jam_masuk, $dataAbsen?->tanggal) &&
                Utility::is8Hour($dataAbsen->tanggal, $jam_masuk, $jam_keluar)
            ){
                $label = "(TELAT)";
            } else if( 
                Utility::isLate($jam_masuk, $dataAbsen?->tanggal) &&
                !Utility::is8Hour($dataAbsen->tanggal, $jam_masuk, $jam_keluar)
            ){
                $label = "(PULANG CEPAT)";
            } else if( 
                !Utility::isLate($jam_masuk, $dataAbsen?->tanggal) &&
                !Utility::is8Hour($dataAbsen->tanggal, $jam_masuk, $jam_keluar)
            ){
                $label = "<>(PULANG CEPAT)";
            }
        } else{
            $label = "";
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