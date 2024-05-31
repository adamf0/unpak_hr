<?php

namespace Architecture\External\Persistance\Queries\SPPD;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\SPPD\FirstData\GetSPPDQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\AnggotaSPPD;
use Architecture\Domain\Entity\DosenEntitas;
use Architecture\Domain\Entity\FakultasEntitas;
use Architecture\Domain\Entity\JenisSPPDEntitas;
use Architecture\Domain\Entity\PegawaiEntitas;
use Architecture\Domain\Entity\ProdiEntitas;
use Architecture\Domain\Entity\SPPDEntitas;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Persistance\ORM\SPPD as SPPDModel;
use Architecture\Shared\TypeData;
use Exception;

class GetSPPDQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetSPPDQuery $query)
    {
        $data = SPPDModel::with(['JenisSPPD','Dosen','Dosen.Fakultas','Dosen.Prodi','Pegawai','Anggota','Anggota.Dosen','Anggota.Pegawai'])->where('id',$query->GetId())->first();
        if($query->getOption()==TypeData::Default) return $data;

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

        return Creator::buildSPPD(SPPDEntitas::make(
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
                $data->Pegawai?->nip,
                $data->Pegawai?->nama,
                $data->Pegawai?->unit,
            )):null,
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
            $data->dokumen_anggaran,
            $list_anggota
        ));
    }
}