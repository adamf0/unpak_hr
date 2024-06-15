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
        $kode       = match(true){
            !empty($pengguna->nidn)=>$pengguna->nidn,
            !empty($pengguna->nip)=>$pengguna->nip,
            default=>"NA",
        };

        if(empty($i_p_prev) || $i_p_prev!=$i_p_curr){
            if(!empty($pengguna->nidn)){
                $this->laporan = DB::table('laporan_merge_absen_izin_cuti')->where('nidn',$pengguna->nidn)->get();
            } else if(!empty($pengguna->nip)){
                $this->laporan = DB::table('laporan_merge_absen_izin_cuti')->where('nip',$pengguna->nip)->get();
            }
            $dosen      = Dosen::where('NIDN',$pengguna->nidn)->first();
            $pegawai    = NPribadi::where('nip',$pengguna->nip)->first();
            
            $list_data[$kode]["pengguna"] = $dosen??$pegawai;
            $list_data[$kode]["type"] = match(true){
                !empty($pengguna->nidn)=>"dosen",
                !empty($pengguna->nip)=>"pegawai",
                default=>"NA",
            };
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

    function formatPeriod($endtime, $starttime)
{

  $duration = $endtime - $starttime;

  $hours = (int) ($duration / 60 / 60);

  $minutes = (int) ($duration / 60) - $hours * 60;

  $seconds = (int) $duration - $hours * 60 * 60 - $minutes * 60;

  return ($hours == 0 ? "00":$hours) . ":" . ($minutes == 0 ? "00":($minutes < 10? "0".$minutes:$minutes)) . ":" . ($seconds == 0 ? "00":($seconds < 10? "0".$seconds:$seconds));
}
    public function handle(GetAllLaporanAbsenQuery $query)
    {
        if($query->getOption()==TypeData::Entity) throw new Exception("no implementation GetAllLaporanAbsenQuery to Entity");

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time','-1');
        date_default_timezone_set('Asia/Jakarta');

        $start = empty($query->GetTanggalMulai())? date('Y-m-01'):date('Y-m-d',strtotime($query->GetTanggalMulai()));
        $end = empty($query->GetTanggalAkhir())? date('Y-m-t'):date('Y-m-d',strtotime($query->GetTanggalAkhir()));
        
        $start = microtime(true);
        $this->list_pengguna = DB::table('laporan_merge_absen_izin_cuti')->select('nidn','nip')->distinct();
        if(!empty($query->GetNIDN())){
            $this->list_pengguna = $this->list_pengguna->where('nidn',$query->GetNIDN());
        } else if(!empty($query->GetNIP())){
            $this->list_pengguna = $this->list_pengguna->where('nip',$query->GetNIP());
        }
        $this->list_pengguna = $this->list_pengguna->get();
        $end = microtime(true);
        // dump(($end-$start)*100);

        for ($date = Carbon::now()->setTimezone('Asia/Jakarta')->startOfMonth(); $date->lte($end); $date->addDay()) {
            $this->list_tanggal[] = $date->copy()->format('Y-m-d');
        }
        return [
            "list_tanggal"=>$this->list_tanggal,
            "list_data"=>array_values($this->gen_data_tbl(null,0,0,[])),
            "start"=>date('d F Y', strtotime($start)),
            "end"=>date('d F Y', strtotime($end)),
        ];
    }
}