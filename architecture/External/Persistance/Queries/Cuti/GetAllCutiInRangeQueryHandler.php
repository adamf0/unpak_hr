<?php

namespace Architecture\External\Persistance\Queries\Cuti;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Cuti\List\GetAllCutiInRangeQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\CutiEntitas;
use Architecture\Domain\Entity\DosenEntitas;
use Architecture\Domain\Entity\FakultasEntitas;
use Architecture\Domain\Entity\JenisCutiEntitas;
use Architecture\Domain\Entity\PegawaiEntitas;
use Architecture\Domain\Entity\ProdiEntitas;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Persistance\ORM\Cuti as CutiModel;
use Architecture\Shared\TypeData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GetAllCutiInRangeQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetAllCutiInRangeQuery $query)
    {
        $datas = CutiModel::with(['JenisCuti','Dosen','Dosen.Fakultas','Dosen.Prodi','Pegawai','PayrollPegawai','PayrollVerifikasi','EPribadi']);
        if(!empty($query->GetNIDN())){
            $datas = $datas->where('nidn',$query->GetNIDN())->orWhereHas('EPribadi', fn($subQuery) => $subQuery->where('nidn', $query->GetNIDN()) );
        }
        if(!empty($query->GetNIP())){
            $datas = $datas->where('nip',$query->GetNIP());
        }

        $dates = $query->GetDateRange();
        if(count($query->GetDateRange())==2){
            $datas = $datas->where(function($q) use($dates){
                $q->where('tanggal_mulai','>=',$dates[0])->where('tanggal_akhir','<=',$dates[0]);
            }); 
            $datas = $datas->orWhere(function($q) use($dates){
                $q->where('tanggal_mulai','>=',$dates[1])->where('tanggal_akhir','<=',$dates[1]);
            }); 
        } else if(count($query->GetDateRange())==1){
            $datas = $datas->where('tanggal_mulai','>=',$dates[0])->where('tanggal_akhir','<=',$dates[0]);
        }
        $datas = $datas->orderBy('id', 'DESC')->get();

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