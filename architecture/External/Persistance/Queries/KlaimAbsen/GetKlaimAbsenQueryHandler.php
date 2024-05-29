<?php

namespace Architecture\External\Persistance\Queries\KlaimAbsen;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\KlaimAbsen\FirstData\GetKlaimAbsenQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\DosenEntitas;
use Architecture\Domain\Entity\FakultasEntitas;
use Architecture\Domain\Entity\KlaimAbsenEntitas;
use Architecture\Domain\Entity\PegawaiEntitas;
use Architecture\Domain\Entity\PresensiEntitas;
use Architecture\Domain\Entity\ProdiEntitas;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Persistance\ORM\KlaimAbsen as KlaimAbsenModel;
use Architecture\Shared\TypeData;

class GetKlaimAbsenQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetKlaimAbsenQuery $query)
    {
        $data = KlaimAbsenModel::with(['Presensi','Dosen','Dosen.Fakultas','Dosen.Prodi','Pegawai'])->where('id',$query->GetId())->first();

        if($query->getOption()==TypeData::Default) return $data;

        return Creator::buildKlaimAbsen(KlaimAbsenEntitas::make(
            $data->id,
            !is_null($data->Dosen)? Creator::buildDosen(DosenEntitas::make(
                $data->Dosen?->NIDN,
                $data->Dosen?->nama_dosen,
                !is_null($data->Dosen->Fakultas)? Creator::buildFakultas(FakultasEntitas::make(
                    $data->Dosen?->Fakultas?->kode_fakultas,
                    $data->Dosen?->Fakultas?->nama_fakultas,
                )):null,
                !is_null($data->Prodi)? Creator::buildProdi(ProdiEntitas::make(
                    $data->Prodi?->kode_prodi,
                    $data->Prodi?->nama_prodi,
                )):null,
            )):null,
            !is_null($data->Pegawai)? Creator::buildPegawai(PegawaiEntitas::make(
                $data->Pegawai?->nip,
                $data->Pegawai?->nama,
                $data->Pegawai?->unit,
            )):null,
            !is_null($data->Presensi)? Creator::buildPresensi(PresensiEntitas::make(
                $data->Presensi?->id,
                $data->Presensi?->nidn,
                $data->Presensi?->nip,
                New Date($data->Presensi?->tanggal),
                $data->Presensi?->absen_masuk==null? null:new Date($data->Presensi?->absen_masuk),
                $data->Presensi?->absen_keluar==null? null:new Date($data->Presensi?->absen_keluar),
                $data->Presensi?->catatan_telat,
                $data->Presensi?->catatan_pulang,
                $data->Presensi?->otomatis_keluar,
            )):null,
            $data->jam_masuk,
            $data->jam_keluar,
            $data->tujuan,
            $data->dokumen,
            $data->catatan,
            $data->status,
        ));
    }
}