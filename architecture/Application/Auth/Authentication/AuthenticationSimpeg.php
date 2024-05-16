<?php

namespace Architecture\Application\Auth\Authentication;

use Architecture\Application\Abstractions\Pattern\IAuthentication;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\Pengguna;
use Architecture\Domain\Entity\PenggunaEntitas;
use Architecture\External\Persistance\ORM\Pengguna as ModelPengguna;
use Exception;

class AuthenticationSimpeg implements IAuthentication {
    public function __construct() {}

    public function Authentication(Pengguna $pengguna) {
        $listPengguna       = ModelPengguna::with(['NPribadi'=>fn($query)=>$query->select('id_n_pribadi','nip','nama','status_pegawai')])->where('username',$pengguna->GetUsername())
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
                                        strtolower($row->level),
                                        true
                                    ));
                                })
                                ->filter(fn($data)=> $data->AuthenticationSimpeg($pengguna->GetUsername(),$pengguna->GetPassword()) );

        if($listPengguna->count()>1) throw new Exception("akun ".$pengguna->GetUsername()." lebih dari 1");
        if($listPengguna->count()==0) throw new Exception("akun tidak ditemukan");
        
        $penggunaSimak      = $listPengguna->first();
        if(!$penggunaSimak->IsActive()) throw new Exception("akun sudah tidak aktif");

        return $penggunaSimak;
    }
}