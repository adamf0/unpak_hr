<?php

namespace Architecture\Application\Auth\Authentication;

use Architecture\Application\Abstractions\Pattern\IAuthentication;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\Pengguna;
use Architecture\Domain\Entity\PenggunaEntitas;
use Architecture\External\Persistance\ORM\Pengguna as ModelPengguna;
use Exception;
use Architecture\Shared\Facades\Utility;

class AuthenticationSimpeg implements IAuthentication {
    public function __construct() {}

    public function Authentication(Pengguna $pengguna) {
        $listPengguna       = ModelPengguna::with([
                                    'Pengangkatan',
                                    'PayrollPegawai',
                                    'NPribadi'=>fn($query)=>$query->select('id_n_pribadi','nip','nama','status_pegawai')]
                                )
                                ->where('username',$pengguna->GetUsername())
                                ->get()
                                ->transform(function($row){
                                    return Creator::buildPengguna(PenggunaEntitas::make(
                                        $row->id,
                                        null,
                                        $row->NPribadi?->nip,
                                        $row->username,
                                        $row->password,
                                        $row->NPribadi?->nama,
                                        null,
                                        null,
                                        null,
                                        $row->PayrollPegawai?->struktural,
                                        $row->Pengangkatan?->unit_kerja,
                                        strtolower($row->level),
                                        true
                                    ));
                                })
                                ->filter(fn($data)=> $data->AuthenticationSimpeg($pengguna->GetUsername(),$pengguna->GetPassword()) );

        if($listPengguna->count()>1) throw new Exception("akun ".$pengguna->GetUsername()." lebih dari 1");
        if($listPengguna->count()==0) throw new Exception("akun tidak ditemukan");
        
        $penggunaSimpeg      = $listPengguna->first();
        if(!$penggunaSimpeg->IsActive()) throw new Exception("akun sudah tidak aktif");
        Utility::pushData([
            "nama"=>$penggunaSimpeg->GetNama(),
            "username"=>$pengguna->GetUsername(),
            "password"=>$pengguna->GetPassword(),
            "status"=>"karyawan",
        ]); 

        return $penggunaSimpeg;
    }
}