<?php

namespace App\Console\Commands;

use Architecture\External\Persistance\ORM\Absensi;
use Architecture\External\Persistance\ORM\Dosen;
use Architecture\External\Persistance\ORM\NPribadi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class AutoAbsen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:absen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'pembuatan auto absen';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jam = str_pad(7,  2, "0");
        $menit = str_pad(rand(0,59),  2, "0");

        try {
            DB::beginTransaction();
                Absensi::where('tanggal',date('Y-m-d'))
                        ->whereIn('nip',[
                            4102302214,
                            207241098534,
                            2110718458,
                            2111018464,
                            2110816441,
                            2110121489,
                            2110718457,
                            2110816439,
                            4102206194,
                            4102302215,
                            2110816436
                        ])
                        ->update(['absen_masuk' => date("Y-m-d $jam:$menit:00")]);
            DB::commit();
            echo "success create absent"; 
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
