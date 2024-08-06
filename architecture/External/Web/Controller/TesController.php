<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Shared\Facades\Utility;

class TesController extends Controller
{
    public function __construct(
    ) {}
    
    public function tes(){
        $jam_masuk = "2024-08-02 08:00:04";
        $jam_keluar = "2024-08-02 14:00:00";
        $tanggal = "2024-08-02";

        if( 
            Utility::isLate($jam_masuk, $tanggal) &&
            !Utility::is8Hour($tanggal, $jam_masuk, $jam_keluar)
        ){
            $label1 = "(PULANG CEPAT)";
        } else if( 
            !Utility::isLate($jam_masuk, $tanggal) &&
            !Utility::is8Hour($tanggal, $jam_masuk, $jam_keluar)
        ){
            $label1 = "<>(PULANG CEPAT)";
        } else{
            $label1 = "ok";
        }
        dump($label1);

        $jam_masuk = "2024-08-03 08:14:46";
        $jam_keluar = "2024-08-03 12:00:00";
        $tanggal = "2024-08-03";

        if( 
            Utility::isLate($jam_masuk, $tanggal) &&
            !Utility::is8Hour($tanggal, $jam_masuk, $jam_keluar)
        ){
            $label2 = "(PULANG CEPAT)";
        } else if( 
            !Utility::isLate($jam_masuk, $tanggal) &&
            !Utility::is8Hour($tanggal, $jam_masuk, $jam_keluar)
        ){
            $label2 = "<>(PULANG CEPAT)";
        } else{
            $label2 = "ok";
        }
        dump($label2);

        $jam_masuk = "2024-07-29 08:32:25";
        $jam_keluar = "2024-07-29 15:00:00";
        $tanggal = "2024-07-29";

        if( 
            Utility::isLate($jam_masuk, $tanggal) &&
            !Utility::is8Hour($tanggal, $jam_masuk, $jam_keluar)
        ){
            $label3 = "(PULANG CEPAT)";
        } else if( 
            !Utility::isLate($jam_masuk, $tanggal) &&
            !Utility::is8Hour($tanggal, $jam_masuk, $jam_keluar)
        ){
            $label3 = "<>(PULANG CEPAT)";
        } else{
            $label3 = "ok";
        }

        dd($label3);
    }
}
