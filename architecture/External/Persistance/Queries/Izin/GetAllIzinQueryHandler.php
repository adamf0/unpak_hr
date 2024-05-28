<?php

namespace Architecture\External\Persistance\Queries\Izin;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Izin\List\GetAllIzinQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\DosenEntitas;
use Architecture\Domain\Entity\FakultasEntitas;
use Architecture\Domain\Entity\IzinEntitas;
use Architecture\Domain\Entity\JenisIzinEntitas;
use Architecture\Domain\Entity\PegawaiEntitas;
use Architecture\Domain\Entity\ProdiEntitas;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Persistance\ORM\Izin as IzinModel;
use Architecture\Shared\TypeData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GetAllIzinQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetAllIzinQuery $query)
    {
        $datas = IzinModel::with(['JenisIzin','Dosen','Dosen.Fakultas','Dosen.Prodi','Pegawai']);
        if(!empty($query->GetNIDN())){
            $datas = $datas->where('nidn',$query->GetNIDN());
        }
        if(!empty($query->GetNIP())){
            $datas = $datas->where('nip',$query->GetNIP());
        }
        if(!empty($query->GetTahun())){
            $datas = $datas->where(DB::raw('YEAR(tanggal_pengajuan)'),$query->GetTahun());
        }
        $datas = $datas->orderBy('id', 'DESC')->get();

        if($query->getOption()==TypeData::Default) return new Collection($datas);

        return $datas->transform(fn($data)=> Creator::buildIzin(IzinEntitas::make(
            $data->id,
            !is_null($data->Dosen)? Creator::buildDosen(DosenEntitas::make(
                $data->Dosen->NIDN,
                $data->Dosen->nama,
                !is_null($data->Dosen->Fakultas)? Creator::buildFakultas(FakultasEntitas::make(
                    $data->Dosen->Fakultas?->kode_fakultas,
                    $data->Dosen->Fakultas?->nama_fakultas,
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
            new Date($data->tanggal_pengajuan),
            $data->tujuan,
            Creator::buildJenisIzin(JenisIzinEntitas::make(
                $data->JenisIzin?->id,
                $data->JenisIzin?->nama,
            )),
            $data->dokumen,
            $data->catatan,
            $data->status,
        )) );
    }
}