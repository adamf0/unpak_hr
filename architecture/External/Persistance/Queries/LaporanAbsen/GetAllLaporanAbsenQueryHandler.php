<?php

namespace Architecture\External\Persistance\Queries\LaporanAbsen;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\LaporanAbsen\List\GetAllLaporanAbsenQuery;
use Architecture\External\Persistance\ORM\Dosen;
use Architecture\External\Persistance\ORM\NPribadi;
use Architecture\Shared\TypeData;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetAllLaporanAbsenQueryHandler extends Query
{
    public Collection $laporan;
    public $list_pengguna;
    public $list_tanggal = [];

    public function __construct() {
        $this->laporan = collect([]);
    }

    public function gen_data_tbl($i_p_prev=null,$i_p_curr=0,$i_t=0,$list_data=[]){
        if(count($this->list_pengguna)==0){
            throw new Exception("daftar pengguna datanya kosong");
        }
        if(!isset($this->list_pengguna[$i_p_curr])){
            return $list_data;
        }
        if(count($this->list_tanggal)==0){
            throw new Exception("daftar tanggal datanya kosong");
        }
        if(!isset($this->list_tanggal[$i_t])){
            if($i_p_curr<count($this->list_pengguna)){
                return $this->gen_data_tbl($i_p_curr,$i_p_curr+1,0,$list_data);
            }
            return $list_data;
        }

        $tanggal    = $this->list_tanggal[$i_t];
        $pengguna   = $this->list_pengguna[$i_p_curr];
        $kode       = "NA";
        if(!empty($pengguna->nidn)){
            $kode = $pengguna->nidn;
        } else if(!empty($pengguna->nip)){
            $kode = $pengguna->nip;
        }
        // $kode       = match(true){
        //     !empty($pengguna->nidn)=>$pengguna->nidn,
        //     !empty($pengguna->nip)=>$pengguna->nip,
        //     default=>"NA",
        // };

        if(empty($i_p_prev) || $i_p_prev!=$i_p_curr){
            if(!empty($pengguna->nidn)){
                $this->laporan = DB::table('laporan_merge_absen_izin_cuti')->where('nidn',$pengguna->nidn)->get();
            } else if(!empty($pengguna->nip)){
                $this->laporan = DB::table('laporan_merge_absen_izin_cuti')->where('nip',$pengguna->nip)->get();
            }
            $dosen      = Dosen::select('NIDN','nama_dosen','kode_fak','kode_jurusan','kode_prodi','status_aktif')->where('NIDN',$pengguna->nidn)->first();
            $pegawai    = NPribadi::select('nip','nama','status_pegawai')->where('nip',$pengguna->nip)->first();
            
            $list_data[$kode]["pengguna"] = $dosen??$pegawai;
            if(!empty($pengguna->nidn)){
                $list_data[$kode]["type"] = "dosen";
            } else if(!empty($pengguna->nip)){
                $list_data[$kode]["type"] = "pegawai";
            } else{
                $list_data[$kode]["type"] = "NA";
            }
            // $list_data[$kode]["type"] = match(true){
            //     !empty($pengguna->nidn)=>"dosen",
            //     !empty($pengguna->nip)=>"pegawai",
            //     default=>"NA",
            // };
        }
        if($i_t<count($this->list_tanggal)){
            $list_data[$kode][$tanggal] = $this->laporan->where('tanggal',$tanggal)->map(function($item){
                $item->info = (object) json_decode($item->info,true);
                return $item;
            })->values()->toArray();
            return $this->gen_data_tbl($i_p_curr,$i_p_curr,$i_t+1,$list_data);
        } else if($i_p_curr<count($this->list_pengguna)){
            return $this->gen_data_tbl($i_p_curr,$i_p_curr+1,0,$list_data);
        }

        return $list_data;
    }

    public function handle(GetAllLaporanAbsenQuery $query)
    {
        if($query->getOption()==TypeData::Entity) throw new Exception("no implementation GetAllLaporanAbsenQuery to Entity");

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time','-1');
        date_default_timezone_set('Asia/Jakarta');

        $start = Carbon::parse(empty($query->GetTanggalMulai())? date('Y-m-01'):$query->GetTanggalMulai());
        $start_string = date('d F Y', strtotime($start));

        $end = Carbon::parse(empty($query->GetTanggalAkhir())? date('Y-m-t'):$query->GetTanggalAkhir());
        $end_string = date('d F Y', strtotime($end));
                
        $this->list_pengguna = DB::table('laporan_merge_absen_izin_cuti')->select('nidn','nip')->distinct();
        if(!empty($query->GetNIDN())){
            $this->list_pengguna = $this->list_pengguna->where('nidn',$query->GetNIDN());
        } else if(!empty($query->GetNIP())){
            $this->list_pengguna = $this->list_pengguna->where('nip',$query->GetNIP());
        }

        if($query->GetType()=="dosen"){
            $this->list_pengguna = $this->list_pengguna->where(DB::raw("TRIM(nidn)"),'<>','');
        } else if($query->GetType()=="tendik"){
            $this->list_pengguna = $this->list_pengguna->where(DB::raw("TRIM(nip)"),'<>','');
        }
        Log::channel('mysql_query')->info($this->list_pengguna->toRawSql());
        
        $this->list_pengguna = $this->list_pengguna->get();
                                    // ->filter(function($item) use($query){
                                    //     // return match($query->GetType()){
                                    //     //     "dosen"=>!empty($item->nidn),
                                    //     //     "pegawai"=>!empty($item->nip),
                                    //     //     default=>$item
                                    //     // };
                                    //     if($query->GetType()=="dosen"){
                                    //         return !empty($item->nidn);
                                    //     } else if($query->GetType()=="pegawai"){
                                    //         return !empty($item->nip);
                                    //     }
                                    //     return $item;
                                    // })
                                    // ->values();

        for ($date = $start; $date->lte($end); $date->addDay()) {
            $this->list_tanggal[] = $date->copy()->format('Y-m-d');
        }

        return [
            "list_tanggal"=>$this->list_tanggal,
            "list_data"=>array_values($this->gen_data_tbl(null,0,0,[])),
            "start"=>$start_string,
            "end"=>$end_string,
        ];
    }
}