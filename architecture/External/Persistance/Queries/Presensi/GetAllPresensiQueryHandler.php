<?php

namespace Architecture\External\Persistance\Queries\Presensi;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Presensi\List\GetAllPresensiQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\DosenEntitas;
use Architecture\Domain\Entity\FakultasEntitas;
use Architecture\Domain\Entity\PegawaiEntitas;
use Architecture\Domain\Entity\PresensiEntitas;
use Architecture\Domain\Entity\ProdiEntitas;
use Architecture\Domain\ValueObject\Date;
use Architecture\Shared\TypeData;
use Architecture\External\Persistance\ORM\Absensi as ModelAbsensi;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GetAllPresensiQueryHandler extends Query
{
    public function __construct() {}

    function getDosen($source){
        return is_null($source->nip_pegawai)? Creator::buildDosen(DosenEntitas::make(
            $source->nidn_dosen,
            $source->nama_dosen,
            !is_null($source->kode_fakultas)? Creator::buildFakultas(FakultasEntitas::make(
                $source->kode_fakultas,
                $source->nama_fakultas,
            )):null,
            !is_null($source->kode_prodi)? Creator::buildProdi(ProdiEntitas::make(
                $source->kode_prodi,
                $source->nama_prodi,
            )):null,
            // $source->status
        )):null;
    }
    function getPegawai($source){
        return !is_null($source->nip_pegawai)? Creator::buildPegawai(PegawaiEntitas::make(
            null,
            $source->nip_pegawai,
            $source->nama_pegawai,
            $source->unit_kerja,
            // $source->status
        )):null;
    }

    public function handle(GetAllPresensiQuery $query)
    {
        $datas = DB::table('absen as a')
        ->leftJoin('connect_n_pribadi as cnp', 'a.nip', '=', 'cnp.nip')
        ->leftJoin('connect_m_dosen as cmd', 'a.nidn', '=', 'cmd.NIDN')
        ->leftJoin('connect_e_pribadi as cep', 'cmd.NIDN', '=', 'cep.nidn')
        ->leftJoin('connect_m_fakultas as cmf', 'cmd.kode_fak', '=', 'cmf.kode_fakultas')
        ->leftJoin('connect_r_prodi as crp', 'cmd.kode_prodi', '=', 'crp.nama_prodi')
        ->rightJoinSub(
            DB::table('connect_n_pengangkatan')
                ->select('nip', 'unit_kerja')
                ->whereRaw('LOWER(status_n_pengangkatan) = ?', ['berlaku'])
                ->unionAll(
                    DB::table('connect_e_pengangkatan')
                        ->select('nip', 'unit_kerja')
                        ->whereRaw('LOWER(status_berlaku_pengangkatan) = ?', ['berlaku'])
                ),
            'mu',
            function ($join) {
                $join->join('connect_payroll_m_pegawai as cpmp', 'mu.nip', '=', 'cpmp.nip')
                    ->whereNull('cpmp.tgl_keluar')
                    ->orWhereRaw('TRIM(cpmp.tgl_keluar) = ?', ['']);
            }
        )->select(
            'cnp.nip as nip_pegawai',
            'cnp.nama as nama_pegawai',
            'cep.nip as nip_dosen',
            'a.nidn as nidn_dosen',
            'cmd.nama_dosen',
            'cmf.*',
            'crp.*',
            'mu.unit_kerja',
            DB::raw("(CASE 
                WHEN cpmp.tgl_keluar IS NULL OR TRIM(cpmp.tgl_keluar) = '' THEN 'aktif'
                ELSE 'keluar'
            END) as status"),
            'a.*'
        );
        
        // ModelAbsensi::with([
        //     'Dosen',
        //     'Dosen.Fakultas',
        //     'Dosen.Prodi',
        //     'Pegawai'
        // ]);
        if(!empty($query->GetNIDN())){
            $datas = $datas->where('a.nidn',$query->GetNIDN());
        }
        if(!empty($query->GetNIP())){
            $datas = $datas->where('cnp.nip',$query->GetNIP())->orWhere('cep.nip',$query->GetNIP());
        }
        if(!empty($query->GetTahun())){
            $datas = $datas->where(DB::raw('YEAR(a.tanggal)'),$query->GetTahun());
        }
        $datas = $datas->orderBy('a.absen_masuk','DESC')->get();

        if($query->getOption()==TypeData::Default) return new Collection($datas);

        return $datas->transform(fn($data)=> Creator::buildPresensi(PresensiEntitas::make(
            $data->id,
            $this->getDosen($data),
            $this->getPegawai($data),
            New Date($data->tanggal),
            $data->absen_masuk==null? null:new Date($data->absen_masuk),
            $data->absen_keluar==null? null:new Date($data->absen_keluar),
            $data->catatan_telat,
            $data->catatan_pulang,
            $data->otomatis_keluar,
        )) );
    }
}