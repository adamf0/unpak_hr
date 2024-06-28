<?php

namespace Architecture\External\Persistance\Queries\Presensi;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Presensi\List\GetAllPresensiQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\DosenEntitas;
use Architecture\Domain\Entity\FakultasEntitas;
use Architecture\Domain\Entity\PegawaiEntitas;
use Architecture\Domain\Entity\PresensiEntitas;
use Architecture\Domain\Entity\ProdiEntitas;
use Architecture\Domain\ValueObject\Date;
use Architecture\Shared\TypeData;
use Architecture\External\Persistance\ORM\Absensi as ModelAbsensi;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GetAllPresensiQueryHandler extends Query
{
    public function __construct() {}

    function getDosen($source){
        return is_null($source->nip_pegawai)? Creator::buildDosen(DosenEntitas::make(
            $source->nidn_dosen,
            $source->nama_dosen,
            !is_null($source->kode_fakultas)? Creator::buildFakultas(FakultasEntitas::make(
                $source->kode_fakultas,
                $source->nama_fakultas,
            )):null,
            !is_null($source->kode_prodi)? Creator::buildProdi(ProdiEntitas::make(
                $source->kode_prodi,
                $source->nama_prodi,
            )):null,
            // $source->status
        )):null;
    }
    function getPegawai($source){
        return !is_null($source->nip_pegawai)? Creator::buildPegawai(PegawaiEntitas::make(
            null,
            $source->nip_pegawai,
            $source->nama_pegawai,
            $source->unit_kerja,
            // $source->status
        )):null;
    }

    public function handle(GetAllPresensiQuery $query)
    {
        $datas = DB::table('presensi_view');        
        // ModelAbsensi::with([
        //     'Dosen',
        //     'Dosen.Fakultas',
        //     'Dosen.Prodi',
        //     'Pegawai'
        // ]);
        if(!empty($query->GetNIDN())){
            $datas = $datas->where('nidn',$query->GetNIDN());
        }
        if(!empty($query->GetNIP())){
            $datas = $datas->where('nip_pegawai',$query->GetNIP())->orWhere('nip_dosen',$query->GetNIP());
        }
        if(!empty($query->GetTahun())){
            $datas = $datas->where(DB::raw('YEAR(tanggal)'),$query->GetTahun());
        }
        $datas = $datas->orderBy('absen_masuk','DESC')->get();
        dd($datas);

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