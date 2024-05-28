<?php

namespace Architecture\External\Persistance\Queries\Cuti;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Cuti\List\GetAllCutiByNIPQuery;
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

class GetAllCutiByNIPQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetAllCutiByNIPQuery $query)
    {
        $datas = CutiModel::with(['JenisCuti','Dosen','Dosen.Fakultas','Dosen.Prodi','Pegawai'])->where('nip',$query->GetNIP())->get();

        if($query->getOption()==TypeData::Default) return new Collection($datas);

        return $datas->transform(fn($data)=> Creator::buildCuti(CutiEntitas::make(
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