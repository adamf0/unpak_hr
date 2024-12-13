<?php

namespace Architecture\External\Persistance\Queries\Cuti;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Cuti\List\GetAllCutiQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\CutiEntitas;
use Architecture\Domain\Entity\DosenEntitas;
use Architecture\Domain\Entity\FakultasEntitas;
use Architecture\Domain\Entity\JenisCutiEntitas;
use Architecture\Domain\Entity\PegawaiEntitas;
use Architecture\Domain\Entity\ProdiEntitas;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Persistance\ORM\Cuti as CutiModel;
use Architecture\External\Persistance\ORM\EPribadi;
use Architecture\Shared\TypeData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetAllCutiQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetAllCutiQuery $query)
    {
        $nip = EPribadi::where('nidn',$query->GetNIDN())->first()?->nip;
        $datas = CutiModel::with(['JenisCuti'])->get();
        
        // if(!empty($query->GetNIDN())){
        //     if($query->GetSemua()){
        //         if($query->IsVerificator()){
        //             $datas = $datas->whereIn('verifikasi', [$nip,$query->GetNIP()]);
        //         } else{
        //             $datas = $datas->where(fn($q)=> $q->where('nidn',$query->GetNIDN()));
        //             // ->orWhereHas('EPribadiRemote', fn($subQuery) => $subQuery->where('nidn', $query->GetNIDN()) ) );
        //         }
        //     } else{
        //         $datas = $query->IsVerificator()? 
        //             $datas->where('nidn',$query->GetNIDN())->orWhere('verifikasi',$query->GetNIDN()):
        //             $datas->where('nidn',$query->GetNIDN());
        //     }
        // } else if(!empty($query->GetNIP())){
        //     if($query->IsVerificator()){
        //         $datas = $datas->whereIn('verifikasi',[$query->GetNIP(),$nip]);
        //     } else{
        //         $datas = $datas->where('nip',$query->GetNIP());
        //     }
        // } else if($query->IsVerificator()){
        //     $datas = $datas->whereIn('status',["menunggu verifikasi sdm","tolak sdm","terima sdm"]);
        // }
        // if(!empty($query->GetTahun())){
        //     $datas = $datas->where(DB::raw('YEAR(tanggal_mulai)'),'>=',$query->GetTahun())->where(DB::raw('YEAR(tanggal_akhir)'),'<=',$query->GetTahun());
        // }
        Log::channel('mysql_query')->info($datas->toRawSql());
        $datas = $datas->orderBy('id', 'DESC')->get();
        dd($datas);
        
        if($query->getOption()==TypeData::Default) return new Collection($datas);

        return $datas->transform(fn($data)=> Creator::buildCuti(CutiEntitas::make(
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
            Creator::buildJenisCuti(JenisCutiEntitas::make(
                $data->JenisCuti->id,
                $data->JenisCuti->nama,
                $data->JenisCuti->min,
                $data->JenisCuti->max,
                $data->JenisCuti->dokumen,
                $data->JenisCuti->kondisi,
            )),
            $data->lama_cuti,
            New Date($data->tanggal_mulai),
            $data->tanggal_akhir!=null? New Date($data->tanggal_akhir):null,
            $data->tujuan,
            $data->dokumen,
            !is_null($data->PayrollVerifikasi)? Creator::buildPegawai(PegawaiEntitas::make(
                $data->EPribadi?->nidn,
                $data->PayrollVerifikasi?->nip,
                $data->PayrollVerifikasi?->nama,
                $data->PayrollVerifikasi?->fakultas,
            )):null,
            $data->catatan,
            $data->status,
        )) );
    }
}