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
        try {
            DB::beginTransaction();
                Absensi::where('tanggal',date('Y-m-d'))
                        ->whereIn('nip',[
                            4102206194,
                            2110816436,
                            4102302214,
                            2111018464,
                            2110816439,
                            2110718457,
                            207241098534,
                            2110816441,
                            2110121489
                        ])
                        ->update(['absen_masuk' => date('Y-m-d 06:00:00')]);
            DB::commit();
            echo "success create absent"; 
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
