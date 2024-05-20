<?php

namespace App\Console\Commands;

use Architecture\External\Persistance\ORM\Absensi;
use Architecture\External\Persistance\ORM\Dosen;
use Architecture\External\Persistance\ORM\NPribadi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class CreateAbsen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:absen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'pembuatan create absen';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $list_pegawai = NPribadi::select('nip')->get();
            foreach($list_pegawai as $pegawai){
                $check = Absensi::where('nip',$pegawai->nip)->where('tanggal',date('Y-m-d'))->count();
                if($check==0){
                    $absen = new Absensi();
                    $absen->nip = $pegawai->nip;
                    $absen->tanggal = now();
                    $absen->save();
                }
            }
            $list_dosen = Dosen::select('nidn')->get();
            foreach($list_dosen as $dosen){
                $check = Absensi::where('nidn',$dosen->nidn)->where('tanggal',date('Y-m-d'))->count();
                if($check==0){
                    $absen = new Absensi();
                    $absen->nidn = $dosen->nidn;
                    $absen->tanggal = now();
                    $absen->save();
                }
            }
            DB::commit();
            echo "success create absent"; 
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
