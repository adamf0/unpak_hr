<?php

namespace Architecture\External\Persistance\Queries\SPPD;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\SPPD\List\GetAllSPPDQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\AnggotaSPPD;
use Architecture\Domain\Entity\DosenEntitas;
use Architecture\Domain\Entity\FakultasEntitas;
use Architecture\Domain\Entity\JenisSPPDEntitas;
use Architecture\Domain\Entity\PegawaiEntitas;
use Architecture\Domain\Entity\ProdiEntitas;
use Architecture\Domain\Entity\SPPDEntitas;
use Architecture\Domain\ValueObject\Date;
use Architecture\Domain\ValueObject\File;
use Architecture\External\Persistance\ORM\SPPD as SPPDModel;
use Architecture\Shared\TypeData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetAllSPPDQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetAllSPPDQuery $query)
    {
        $datas = SPPDModel::with(['JenisSPPD','Dosen','Dosen.Fakultas','Dosen.Prodi','Pegawai','Anggota','Anggota.Dosen','Anggota.Dosen.Fakultas','Anggota.Dosen.Prodi','Anggota.Pegawai','FileLaporan','PayrollPegawai','PayrollVerifikasi','EPribadiRemote']);
        if(!empty($query->GetNIDN())){
            if($query->GetSemua()){
                $datas = $datas->where(function($q) use($query){
                    $q->where('verifikasi',$query->GetNIDN())->orWhere('verifikasi',$query->GetNIP());
                })
                ->orWhere(function($q) use($query){
                    $q->where('nidn',$query->GetNIDN())->orWhereHas('Anggota', fn($subQuery) => $subQuery->where('nidn', $query->GetNIDN()) );
                });
                // ->orWhereHas('EPribadiRemote', fn($subQuery) => $subQuery->where('nidn', $query->GetNIDN()) );
            } else{
                $datas = $datas->where(function($q) use($query){
                                    $q->where('verifikasi',$query->GetNIDN())->orWhere('verifikasi',$query->GetNIP());
                                })
                                ->orWhere(fn($q)=> 
                                    $q->where('nidn',$query->GetNIDN())->orWhereHas('Anggota', fn($subQuery) => $subQuery->where('nidn', $query->GetNIDN()) )
                                );
            }
        }
        if(!empty($query->GetNIP())){
            $datas = $datas->where(function($q) use($query){
                            $q->where('verifikasi',$query->GetNIDN())->orWhere('verifikasi',$query->GetNIP());
                        })
                        ->orWhere(fn($q)=> $q->where('nip',$query->GetNIP())->orWhereHas('Anggota', fn($subQuery) => $subQuery->where('nip', $query->GetNIP()) ));
        }
        if(!empty($query->GetTahun())){
            $datas = $datas->where(DB::raw('YEAR(tanggal_berangkat)'),'>=',$query->GetTahun())->where(DB::raw('YEAR(tanggal_kembali)'),'<=',$query->GetTahun());
        }
        Log::info($datas->toRawSql());
        $datas = $datas->orderBy('id', 'DESC')->get();
        // if(!is_null($query->GetNIDN())){
        //     $datas = $datas->filter( function($item) use($query){
        //         $asMember = ($item->Anggota??collect([]))->filter(fn($itemAnggota)=>$itemAnggota?->Dosen?->NIDN==$query->GetNIDN())->count()>0;  
        //         return $item->nidn==$query->GetNIDN() || $asMember;
        //     });
        // }
        // if(!is_null($query->GetNIP())){
        //     $datas = $datas->filter( function($item) use($query){
        //         $asMember = ($item->Anggota??collect([]))->filter(fn($itemAnggota)=>$itemAnggota->Pegawai?->nip==$query->GetNIP())->count()>0;
        //         return $item->nip==$query->GetNIP() || $asMember;
        //     });
        // }
        // $datas = $datas->values();

        if($query->getOption()==TypeData::Default) return new Collection($datas);

        return $datas->transform(function($data){
            $list_anggota = collect([]);
            if(!is_null($data->Anggota)){
                $list_anggota = collect($data->Anggota->reduce(function ($carry, $item){
                    // $nama = match (true) {
                    //     !is_null($item->Dosen) && !is_null($item->Pegawai)=> "Error",
                    //     !is_null($item->Dosen)=> $item->Dosen->nama_dosen,
                    //     !is_null($item->Pegawai)=> $item->Pegawai->nama,
                    //     default=> "NA"
                    // };
                    $nama = "NA";
                    if(!is_null($item->Dosen) && !is_null($item->Pegawai)){
                        $nama = "Error";
                    } else if(!is_null($item->Dosen)){
                        $nama = $item->Dosen->nama_dosen;
                    } else if(!is_null($item->Pegawai)){
                        $nama = $item->Pegawai->nama;
                    }
                    $carry[] = new AnggotaSPPD($item->id,$item->nidn,$item->nip,$nama);
                    return $carry;
                },[]));
            }

            $files = $data->FileLaporan ?? collect([]);
            $foto_kegiatan = $files->filter(fn($item) => $item->type === "foto_kegiatan")->values()->transform(fn($item)=>new File($item->file,"dokumen_laporan_sppd"));
            $undangan = $files->filter(fn($item) => $item->type === "undangan")->values()->transform(fn($item)=>new File($item->file,"dokumen_laporan_sppd"));

            $item = Creator::buildSPPD(SPPDEntitas::make(
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
                Creator::buildJenisSPPD(JenisSPPDEntitas::make(
                    $data->JenisSPPD?->id,
                    $data->JenisSPPD?->nama,
                )),
                new Date($data->tanggal_berangkat),
                new Date($data->tanggal_kembali),
                $data->tujuan,
                $data->keterangan,
                $data->sarana_transportasi,
                !is_null($data->PayrollVerifikasi)? Creator::buildPegawai(PegawaiEntitas::make(
                    $data->EPribadiRemote?->nidn,
                    $data->PayrollVerifikasi?->nip,
                    $data->PayrollVerifikasi?->nama,
                    $data->PayrollVerifikasi?->fakultas,
                )):null,
                $data->status,
                $data->catatan,
                $data->dokumen_anggaran,
                $list_anggota,
                $data->intisari,
                $data->kontribusi,
                $data->rencana_tindak_lanjut,
                $data->rencana_waktu_tindak_lanjut,
                $foto_kegiatan,
                $undangan,
                !empty($data->file_sppd_laporan)? new File($data->file_sppd_laporan, "dokumen_sppd_merge"):null
            ));

            return $item;
        });
    }
}