<?php

namespace Architecture\External\Persistance\Queries\SPPD;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\SPPD\List\GetAllSPPDByNIDNQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\AnggotaSPPD;
use Architecture\Domain\Entity\JenisSPPDEntitas;
use Architecture\Domain\Entity\SPPDEntitas;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Persistance\ORM\SPPD as SPPDModel;
use Architecture\Shared\TypeData;
use Illuminate\Database\Eloquent\Collection;

class GetAllSPPDByNIDNQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetAllSPPDByNIDNQuery $query)
    {
        $datas = SPPDModel::with(['JenisSPPD'])->where('nidn',$query->GetNIDN())->get();

        if($query->getOption()==TypeData::Default) return new Collection($datas);

        return $datas->transform(function($data){
            $list_anggota = collect([]);
            if(!is_null($data->Anggota)){
                $list_anggota = collect($data->Anggota->reduce(function ($carry, $item){
                    $nama = match (true) {
                        !is_null($item->Dosen) && !is_null($item->Pegawai)=> "Error",
                        !is_null($item->Dosen)=> $item->Dosen->nama_dosen,
                        !is_null($item->Pegawai)=> $item->Pegawai->nama,
                        default=> "NA"
                    };
                    $carry[] = new AnggotaSPPD($item->id,$item->nidn,$item->nip,$nama);
                    return $carry;
                },[]));
            }

            $item = Creator::buildSPPD(SPPDEntitas::make(
                $data->id,
                $data->nidn,
                $data->nip,
                Creator::buildJenisSPPD(JenisSPPDEntitas::make(
                    $data->JenisSPPD?->id,
                    $data->JenisSPPD?->nama,
                )),
                new Date($data->tanggal_berangkat),
                new Date($data->tanggal_kembali),
                $data->tujuan,
                $data->keterangan,
                $data->status,
                $data->catatan,
                $list_anggota
            ));

            return $item;
        });
    }
}