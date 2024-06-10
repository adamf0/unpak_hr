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

    function getDosen($source){
        return !is_null($source->Dosen)? Creator::buildDosen(DosenEntitas::make(
            $source->Dosen?->NIDN,
            $source->Dosen?->nama_dosen,
            !is_null($source->Dosen->Fakultas)? Creator::buildFakultas(FakultasEntitas::make(
                $source->Dosen?->Fakultas?->kode_fakultas,
                $source->Dosen?->Fakultas?->nama_fakultas,
            )):null,
            !is_null($source->Prodi)? Creator::buildProdi(ProdiEntitas::make(
                $source->Prodi?->kode_prodi,
                $source->Prodi?->nama_prodi,
            )):null,
        )):null;
    }
    function getPegawai($source){
        return !is_null($source->Pegawai)? Creator::buildPegawai(PegawaiEntitas::make(
            null,
            $source->Pegawai?->nip,
            $source->Pegawai?->nama,
            $source->Pegawai?->unit,
        )):null;
    }

    public function handle(GetKlaimAbsenQuery $query)
    {
        $data = KlaimAbsenModel::with([
            'Presensi',
            'Presensi.Dosen',
            'Presensi.Dosen.Fakultas',
            'Presensi.Dosen.Prodi',
            'Presensi.Pegawai',
            'Dosen',
            'Dosen.Fakultas',
            'Dosen.Prodi',
            'Pegawai'
        ])->where('id',$query->GetId())->first();

        if($query->getOption()==TypeData::Default) return $data;

        return Creator::buildKlaimAbsen(KlaimAbsenEntitas::make(
            $data->id,
            $this->getDosen($data),
            $this->getPegawai($data),
            !is_null($data->Presensi)? Creator::buildPresensi(PresensiEntitas::make(
                $data->Presensi?->id,
                $this->getDosen($data->Presensi),
                $this->getPegawai($data->Presensi),
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