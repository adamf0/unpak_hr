<?php

namespace Architecture\External\Persistance\Queries\Pegawai;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Pegawai\List\GetAllPegawaiV2Query;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\PegawaiEntitas;
use Architecture\External\Persistance\ORM\PayrollPegawai;
use Architecture\Shared\TypeData;

class GetAllPegawaiV2QueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetAllPegawaiV2Query $query)
    {
        $datas = PayrollPegawai::select('nip','nama','struktural')->whereRaw('LENGTH(nip)>=3');
        if($query->GetStruktural()=="verifikator"){
            $datas = $datas->where('struktural','like','%Wakil Rektor Bid SDM dan Keuangan%')->orWhere('struktural','like','%Wakil Dekan 2%');
        } else if($query->GetStruktural()=="struktural_only"){
            $datas = $datas->where('struktural','!=','');
        }
        $datas = $datas->get();

        if($query->getOption()==TypeData::Default) return $datas;

        return $datas->transForm(fn($data)=> Creator::buildPegawai(PegawaiEntitas::make(
            null,
            $data->nip,
            $data->nama,
            null,
            $data->struktural,
        )) );
    }
}