<?php

namespace Architecture\Application\Auth\Authentication;

use Architecture\Application\Abstractions\Pattern\IAuthentication;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\Pengguna;
use Architecture\Domain\Entity\PenggunaEntitasTanpaStatus;
use Architecture\External\Persistance\ORM\User;
use Exception;

class AuthenticationLocal implements IAuthentication {
    public function __construct() {}

    public function Authentication(Pengguna $pengguna) {
        $listPengguna       = User::where('username',$pengguna->GetUsername())
                                ->get()
                                ->transform(fn($row)=> Creator::buildPengguna(PenggunaEntitasTanpaStatus::make($row->id,$row->username,$row->password,$row->name,null,$row->level)))
                                ->filter(fn($data)=> $data->AuthenticationSystem($pengguna->GetUsername(),$pengguna->GetPassword()) );

        if($listPengguna->count()==0){
            $penggunaSimak  = new AuthenticationSimak();
            return $penggunaSimak->Authentication($pengguna);
        }
        if($listPengguna->count()>1) throw new Exception("akun ".$pengguna->GetUsername()." lebih dari 1");

        return $listPengguna->first();
    }
}