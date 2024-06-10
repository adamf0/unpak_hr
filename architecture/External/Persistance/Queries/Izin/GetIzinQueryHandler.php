<?php

namespace Architecture\External\Persistance\Queries\Izin;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Izin\FirstData\GetIzinQuery;
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

class GetIzinQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetIzinQuery $query)
    {
        $data = IzinModel::with(['JenisIzin','Dosen','Dosen.Fakultas','Dosen.Prodi','Pegawai','PayrollPegawai'])->where('id',$query->GetId())->first();

        if($query->getOption()==TypeData::Default) return $data;

        return Creator::buildIzin(IzinEntitas::make(
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
                $data->Pegawai?->unit,
            )):null,
            new Date($data->tanggal_pengajuan),
            $data->tujuan,
            Creator::buildJenisIzin(JenisIzinEntitas::make(
                $data->JenisIzin?->id,
                $data->JenisIzin?->nama,
            )),
            $data->dokumen,
            !is_null($data->PayrollPegawai)? Creator::buildPegawai(PegawaiEntitas::make(
                $data->EPribadiRemote?->nidn,
                $data->PayrollPegawai?->nip,
                $data->PayrollPegawai?->nama,
                $data->PayrollPegawai?->unit,
            )):null,
            $data->catatan,
            $data->status,
        ));
    }
}