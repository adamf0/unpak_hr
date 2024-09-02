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
use Illuminate\Support\Facades\Log;

class GetAllIzinQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetAllIzinQuery $query)
    {
        $datas = IzinModel::with(['JenisIzin','Dosen','Dosen.Fakultas','Dosen.Prodi','Pegawai','PayrollPegawai','PayrollVerifikasi','EPribadiRemote']);
        if(!empty($query->GetNIDN())){
            if($query->GetSemua()){
                $datas = $datas->where('verifikasi',$query->GetNIDN())
                                ->orWhere(fn($q)=> $q->where('nidn',$query->GetNIDN())->orWhereHas('EPribadiRemote', fn($subQuery) => $subQuery->where('nidn', $query->GetNIDN()) ) );
            } else{
                $datas = $query->IsVerificator()? 
                    $datas->where('nidn',$query->GetNIDN())->orWhere('verifikasi',$query->GetNIDN()):
                    $datas->where('nidn',$query->GetNIDN());
            }
        }
        if(!empty($query->GetNIP())){
            $datas = $query->IsVerificator()? 
                        $datas->where('nip',$query->GetNIP())->orWhere('verifikasi',$query->GetNIP()) : 
                        $datas->where('nip',$query->GetNIP());
        }
        if(!empty($query->GetTahun())){
            $datas = $datas->where(DB::raw('YEAR(tanggal_pengajuan)'),$query->GetTahun());
        }
        $datas = $datas->orderBy('id', 'DESC');
        Log::channel('mysql_query')->info($datas->toRawSql());
        $datas = $datas->get();

        if($query->getOption()==TypeData::Default) return new Collection($datas);

        return $datas->transform(fn($data)=> Creator::buildIzin(IzinEntitas::make(
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
                null,
                $data->Pegawai?->nip,
                $data->Pegawai?->nama,
                $data->PayrollPegawai?->fakultas,
            )):null,
            new Date($data->tanggal_pengajuan),
            $data->tujuan,
            Creator::buildJenisIzin(JenisIzinEntitas::make(
                $data->JenisIzin?->id,
                $data->JenisIzin?->nama,
            )),
            $data->dokumen,
            !is_null($data->PayrollVerifikasi)? Creator::buildPegawai(PegawaiEntitas::make(
                $data->EPribadiRemote?->nidn,
                $data->PayrollVerifikasi?->nip,
                $data->PayrollVerifikasi?->nama,
                $data->PayrollVerifikasi?->fakultas,
            )):null,
            $data->catatan,
            $data->status,
        )) );
    }
}