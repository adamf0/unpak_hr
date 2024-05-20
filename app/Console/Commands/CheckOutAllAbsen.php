<?php

namespace App\Console\Commands;

use Architecture\External\Persistance\ORM\Absensi;
use Architecture\External\Persistance\ORM\Dosen;
use Architecture\External\Persistance\ORM\NPribadi;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class CheckOutAllAbsen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkout:absen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'penutupan absen keluar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $absenKeluar = match(Carbon::now()->dayOfWeek){
                5=>Carbon::now()->format("Y-m-d 14:00:00"),
                6=>Carbon::now()->format("Y-m-d 12:00:00"),
                default=>Carbon::now()->format("Y-m-d 15:00:00")
            };
            $absen = Absensi::whereNotNull('absen_masuk')->whereNull('absen_keluar')->where('tanggal',date('Y-m-d'))->get();
            foreach($absen as $item){
                $absen = Absensi::find($item->id);
                if($absen==null) continue;

                $absen->absen_keluar = $absenKeluar;
                $absen->otomatis_keluar = 0;
                $absen->save();
            }

            DB::commit();
            echo "success checkout absent"; 
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
