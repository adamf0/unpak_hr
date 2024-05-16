<?php
namespace Architecture\Domain\Creational;

use Architecture\Domain\Contract\ICuti;
use Architecture\Domain\Contract\IIzin;
use Architecture\Domain\Contract\IJenisCuti;
use Architecture\Domain\Contract\IJenisIzin;
use Architecture\Domain\Contract\IPengguna;
use Architecture\Domain\Contract\IVideoKegiatan;
use Architecture\Domain\Entity\Cuti;
use Architecture\Domain\Entity\Izin;
use Architecture\Domain\Entity\JenisCuti;
use Architecture\Domain\Entity\JenisIzin;
use Architecture\Domain\Entity\Pengguna;
use Architecture\Domain\Entity\VideoKegiatan;

class Creator{
    // public static function buildDosen(IDosen $factory){
    //     return new Dosen(
    //         $factory->GetNidn(),
    //         $factory->GetNama(),
    //         $factory->GetFakultas(),
    //         $factory->GetKodeJurusan(),
    //         $factory->GetProdi(),
    //         $factory->GetNomorRekening(),
    //         $factory->GetStatus(),
    //     );
    // }
    public static function buildPengguna(IPengguna $factory){
        return new Pengguna(
            $factory->GetId(),
            $factory->GetUsername(),
            $factory->GetPassword(),
            $factory->GetNama(),
            $factory->GetFaculty(),
            $factory->GetProgramStudy(),
            $factory->GetPosition(),
            $factory->GetLevel(),
            $factory->GetActive(),
        );
    }
    public static function buildJenisCuti(IJenisCuti $factory){
        return new JenisCuti(
            $factory->GetId(),
            $factory->GetNama(),
            $factory->GetMin(),
            $factory->GetMax(),
            $factory->GetDokumen(),
            $factory->GetKondisi(),
        );
    }
    public static function buildJenisIzin(IJenisIzin $factory){
        return new JenisIzin(
            $factory->GetId(),
            $factory->GetNama(),
        );
    }
    public static function buildCuti(ICuti $factory){
        return new Cuti(
            $factory->GetId(),
            $factory->GetNIDN(),
            $factory->GetNIP(),
            $factory->GetJenisCuti(),
            $factory->GetLamaCuti(),
            $factory->GetTanggalMulai(),
            $factory->GetTanggalAkhir(),
            $factory->GetTujuan(),
            $factory->GetDokumen(),
            $factory->GetCatatan(),
            $factory->GetStatus(),
        );
    }
    public static function buildIzin(IIzin $factory){
        return new Izin(
            $factory->GetId(),
            $factory->GetNIDN(),
            $factory->GetNIP(),
            $factory->GetTanggalPengajuan(),
            $factory->GetTujuan(),
            $factory->GetJenisIzin(),
            $factory->GetDokumen(),
            $factory->GetCatatan(),
            $factory->GetStatus(),
        );
    }
    public static function buildVideoKegiatan(IVideoKegiatan $factory){
        return new VideoKegiatan(
            $factory->GetId(),
            $factory->GetNama(),
            $factory->GetNilai(),
        );
    }
}