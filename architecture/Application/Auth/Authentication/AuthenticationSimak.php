<?php

namespace Architecture\Application\Auth\Authentication;

use Architecture\Application\Abstractions\Pattern\IAuthentication;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\Pengguna;
use Architecture\Domain\Entity\PenggunaEntitas;
use Architecture\External\Persistance\ORM\UserSimak;
use Exception;
use Architecture\Shared\Facades\Utility;
use Illuminate\Support\Facades\Log;

class AuthenticationSimak implements IAuthentication {
    public function __construct() {}

    public function Authentication(Pengguna $pengguna) {
        $listPengguna       = UserSimak::with([
                                    'Dosen'=>fn($query)=>$query->select('NIDN','kode_fak','kode_prodi'),
                                    'Dosen.EPribadi',
                                    'Dosen.EPribadi.Pengangkatan',
                                    'Dosen.EPribadi.Payroll',
                                    'Dosen.EPribadi.Jafung'=>fn($query)=>$query->select('nip','jafung','status_berlaku_jafung')->where('status_berlaku_jafung','BERLAKU'),
                                ])
                                ->where('username',$pengguna->GetUsername())
                                ->get()
                                ->transform(function($row){
                                    Log::info(json_encode($row));

                                    return Creator::buildPengguna(PenggunaEntitas::make(
                                        $row->userid,
                                        $row->Dosen?->NIDN,
                                        null,
                                        $row->username,
                                        $row->password,
                                        $row->nama,
                                        $row->Dosen?->kode_fak,
                                        $row->Dosen?->kode_prodi,
                                        $row->Dosen?->EPribadi?->Jafung?->jafung,
                                        $row->Dosen?->EPribadi?->Payroll?->struktural,
                                        $row->Dosen?->EPribadi?->Pengangkatan?->unit_kerja,
                                        strtolower($row->level),
                                        $row->aktif=="Y"
                                    ));
                                })
                                ->filter(fn($data)=> $data->AuthenticationSimak($pengguna->GetUsername(),$pengguna->GetPassword()) );

        if($listPengguna->count()>1) throw new Exception("akun ".$pengguna->GetUsername()." lebih dari 1");
        if($listPengguna->count()==0){
            $penggunaSimpeg  = new AuthenticationSimpeg();
            return $penggunaSimpeg->Authentication($pengguna);
        }
        
        $penggunaSimak      = $listPengguna->first();
        if(!$penggunaSimak->IsActive()) throw new Exception("akun sudah tidak aktif");
        if(empty($penggunaSimak->GetName()) || empty($penggunaSimak->GetNIDN())) throw new Exception("data simak tidak ditemukan");

        $data = Utility::pushData([
            "nama"=>$penggunaSimak->GetName(),
            "username"=>$pengguna->GetUsername(),
            "password"=>$pengguna->GetPassword(),
            "status"=>"dosen",
        ]); 
        Log::channel('sync_auth')->info($data);
        
        return $penggunaSimak;
    }
}