<?php

namespace Architecture\External\Persistance\Queries\Presensi;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Presensi\List\GetAllPresensiByNIDNQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\DosenEntitas;
use Architecture\Domain\Entity\FakultasEntitas;
use Architecture\Domain\Entity\PegawaiEntitas;
use Architecture\Domain\Entity\PresensiEntitas;
use Architecture\Domain\Entity\ProdiEntitas;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Persistance\ORM\Absensi as ModelAbsensi;
use Architecture\Shared\TypeData;
use Illuminate\Database\Eloquent\Collection;

class GetAllPresensiByNIDNQueryHandler extends Query
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

    public function handle(GetAllPresensiByNIDNQuery $query)
    {
        $datas = ModelAbsensi::with([
            'Dosen',
            'Dosen.Fakultas',
            'Dosen.Prodi',
            'Pegawai'
        ])
        ->where('tanggal','like',"%".$query->GetTahunBulan()."%")
        ->where('nidn',$query->GetNIDN())
        ->get();

        if($query->getOption()==TypeData::Default) return new Collection($datas);

        return $datas->transform(fn($data)=> Creator::buildPresensi(PresensiEntitas::make(
            $data->id,
            $this->getDosen($data),
            $this->getPegawai($data),
            New Date($data->tanggal),
            $data->absen_masuk==null? null:new Date($data->absen_masuk),
            $data->absen_keluar==null? null:new Date($data->absen_keluar),
            $data->catatan_telat,
            $data->catatan_pulang,
            $data->otomatis_keluar,
        )) );
    }
}