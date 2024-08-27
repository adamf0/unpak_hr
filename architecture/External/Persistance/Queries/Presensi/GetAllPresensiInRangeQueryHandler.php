<?php

namespace Architecture\External\Persistance\Queries\Presensi;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Presensi\List\GetAllPresensiInRangeQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\DosenEntitas;
use Architecture\Domain\Entity\FakultasEntitas;
use Architecture\Domain\Entity\PegawaiEntitas;
use Architecture\Domain\Entity\PresensiEntitas;
use Architecture\Domain\Entity\ProdiEntitas;
use Architecture\Domain\ValueObject\Date;
use Architecture\Shared\TypeData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GetAllPresensiInRangeQueryHandler extends Query
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

    public function handle(GetAllPresensiInRangeQuery $query)
    {
        $datas = DB::table('presensi_view');        
        if(!empty($query->GetNIDN())){
            $datas = $datas->where('nidn_dosen',$query->GetNIDN());
        }
        if(!empty($query->GetNIP())){
            $datas = $datas->where('nip_pegawai',$query->GetNIP())->orWhere('nip_dosen',$query->GetNIP());
        }
        $datas = $datas->whereIn('tanggal',$query->GetDateRange());
        return $datas = $datas->orderBy('absen_masuk','DESC')->toRawSql();

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