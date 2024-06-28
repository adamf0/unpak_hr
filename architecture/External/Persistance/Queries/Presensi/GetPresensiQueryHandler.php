<?php

namespace Architecture\External\Persistance\Queries\Presensi;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Presensi\FirstData\GetPresensiQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\DosenEntitas;
use Architecture\Domain\Entity\FakultasEntitas;
use Architecture\Domain\Entity\PegawaiEntitas;
use Architecture\Domain\Entity\PresensiEntitas;
use Architecture\Domain\Entity\ProdiEntitas;
use Architecture\Domain\ValueObject\Date;
use Architecture\Shared\TypeData;
use Illuminate\Support\Facades\DB;

class GetPresensiQueryHandler extends Query
{
    public function __construct() {}

    function getDosen($source){
        return empty($source->nip_pegawai)? Creator::buildDosen(DosenEntitas::make(
            $source->nidn_dosen,
            $source->nama_dosen,
            !empty($source->kode_fakultas)? Creator::buildFakultas(FakultasEntitas::make(
                $source->kode_fakultas,
                $source->nama_fakultas,
            )):null,
            !empty($source->kode_prodi)? Creator::buildProdi(ProdiEntitas::make(
                $source->kode_prodi,
                $source->nama_prodi,
            )):null,
            $source->unit_kerja,
            $source->status
        )):null;
    }
    function getPegawai($source){
        return !empty($source->nip_pegawai)? Creator::buildPegawai(PegawaiEntitas::make(
            null,
            $source->nip_pegawai,
            $source->nama_pegawai,
            $source->unit_kerja,
            null,
            $source->status
        )):null;
    }

    public function handle(GetPresensiQuery $query)
    {
        $data = DB::table('presensi_view')->where('id',$query->GetId())->first();

        if($query->getOption()==TypeData::Default) return $data;

        return Creator::buildPresensi(PresensiEntitas::make(
            $data->id,
            $this->getDosen($data),
            $this->getPegawai($data),
            New Date($data->tanggal),
            $data->absen_masuk==null? null:new Date($data->absen_masuk),
            $data->absen_keluar==null? null:new Date($data->absen_keluar),
            $data->catatan_telat,
            $data->catatan_pulang,
            $data->otomatis_keluar,
        ));
    }
}