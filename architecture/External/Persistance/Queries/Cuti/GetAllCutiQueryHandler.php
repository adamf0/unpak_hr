<?php

namespace Architecture\External\Persistance\Queries\Cuti;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Cuti\List\GetAllCutiQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\CutiEntitas;
use Architecture\Domain\Entity\JenisCutiEntitas;
use Architecture\Domain\Entity\JenisCutiReferensi;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Persistance\ORM\Cuti as CutiModel;
use Architecture\Shared\TypeData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GetAllCutiQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetAllCutiQuery $query)
    {
        $datas = CutiModel::with(['JenisCuti']);
        if(!empty($query->GetNIDN())){
            $datas = $datas->where('nidn',$query->GetNIDN());
        }
        if(!empty($query->GetNIP())){
            $datas = $datas->where('nip',$query->GetNIP());
        }
        if(!empty($query->GetTahun())){
            $datas = $datas->where(DB::raw('YEAR(tanggal_mulai)'),'>=',$query->GetTahun())->where(DB::raw('YEAR(tanggal_akhir)'),'<=',$query->GetTahun());
        }
        $datas = $datas->get();

        if($query->getOption()==TypeData::Default) return new Collection($datas);

        return $datas->transform(fn($data)=> Creator::buildCuti(CutiEntitas::make(
            $data->id,
            $data->nidn,
            $data->nip,
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
            $data->catatan,
            $data->status,
        )) );
    }
}