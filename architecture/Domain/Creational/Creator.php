<?php
namespace Architecture\Domain\Creational;

use Architecture\Domain\Contract\ICuti;
use Architecture\Domain\Contract\IDosen;
use Architecture\Domain\Contract\IFakultas;
use Architecture\Domain\Contract\IIzin;
use Architecture\Domain\Contract\IJenisCuti;
use Architecture\Domain\Contract\IJenisIzin;
use Architecture\Domain\Contract\IJenisSPPD;
use Architecture\Domain\Contract\IMasterKalendar;
use Architecture\Domain\Contract\IPegawai;
use Architecture\Domain\Contract\IPengguna;
use Architecture\Domain\Contract\IPresensi;
use Architecture\Domain\Contract\IProdi;
use Architecture\Domain\Contract\ISPPD;
use Architecture\Domain\Entity\Cuti;
use Architecture\Domain\Entity\Dosen;
use Architecture\Domain\Entity\Fakultas;
use Architecture\Domain\Entity\Izin;
use Architecture\Domain\Entity\JenisCuti;
use Architecture\Domain\Entity\JenisIzin;
use Architecture\Domain\Entity\JenisSPPD;
use Architecture\Domain\Entity\MasterKalendar;
use Architecture\Domain\Entity\Pegawai;
use Architecture\Domain\Entity\Pengguna;
use Architecture\Domain\Entity\Presensi;
use Architecture\Domain\Entity\Prodi;
use Architecture\Domain\Entity\SPPD;

class Creator{
    public static function buildFakultas(IFakultas $factory){
        return new Fakultas(
            $factory->GetId(),
            $factory->GetNamaFakultas()
        );
    }
    public static function buildProdi(IProdi $factory){
        return new Prodi(
            $factory->GetId(),
            $factory->GetNamaProdi()
        );
    }
    public static function buildDosen(IDosen $factory){
        return new Dosen(
            $factory->GetNidn(),
            $factory->GetNama(),
            $factory->GetFakultas(),
            $factory->GetProdi(),
        );
    }
    public static function buildPegawai(IPegawai $factory){
        return new Pegawai(
            $factory->GetNip(),
            $factory->GetNama(),
            $factory->GetUnit(),
        );
    }
    public static function buildPengguna(IPengguna $factory){
        return new Pengguna(
            $factory->GetId(),
            $factory->GetNIDN(),
            $factory->GetNIP(),
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
    public static function buildJenisSPPD(IJenisSPPD $factory){
        return new JenisSPPD(
            $factory->GetId(),
            $factory->GetNama(),
        );
    }
    public static function buildCuti(ICuti $factory){
        return new Cuti(
            $factory->GetId(),
            $factory->GetDosen(),
            $factory->GetPegawai(),
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
            $factory->GetDosen(),
            $factory->GetPegawai(),
            $factory->GetTanggalPengajuan(),
            $factory->GetTujuan(),
            $factory->GetJenisIzin(),
            $factory->GetDokumen(),
            $factory->GetCatatan(),
            $factory->GetStatus(),
        );
    }
    public static function buildSPPD(ISPPD $factory){
        return new SPPD(
            $factory->GetId(),
            $factory->GetDosen(),
            $factory->GetPegawai(),
            $factory->GetJenisSPPD(),
            $factory->GetTanggalBerangkat(),
            $factory->GetTanggalKembali(),
            $factory->GetTujuan(),
            $factory->GetKeterangan(),
            $factory->GetStatus(),
            $factory->GetCatatan(),
            $factory->GetListAnggota(),
        );
    }
    public static function buildPresensi(IPresensi $factory){
        return new Presensi(
            $factory->GetId(),
            $factory->GetNIDN(),
            $factory->GetNIP(),
            $factory->GetTanggal(),
            $factory->GetAbsenMasuk(),
            $factory->GetAbsenKeluar(),
            $factory->GetCatatanTelat(),
            $factory->GetCatatanPulang(),
            $factory->GetOtomatisKeluar(),
        );
    }
    public static function buildMasterKalendar(IMasterKalendar $factory){
        return new MasterKalendar(
            $factory->GetId(),
            $factory->GetTanggalMulai(),
            $factory->GetTanggalAkhir(),
            $factory->GetKeterangan(),
        );
    }
}