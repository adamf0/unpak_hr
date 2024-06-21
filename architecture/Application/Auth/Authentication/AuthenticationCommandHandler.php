<?php

namespace Architecture\Application\Auth\Authentication;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\PenggunaEntitasAkun;
use Illuminate\Support\Facades\Session;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;

class AuthenticationCommandHandler extends CommandHandler
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}

    public function handle(AuthenticationCommand $command)
    {
        $pengguna       = Creator::buildPengguna(PenggunaEntitasAkun::make($command->getUsername(),$command->getPassword()));
        $dataPengguna   = new AuthenticationLocal();
        $dataPengguna   = $dataPengguna->Authentication($pengguna);

        Session::put('id', $dataPengguna->GetId());
        Session::put('nidn', $dataPengguna->GetNIDN());
        Session::put('nip', $dataPengguna->GetNIP());
        Session::put('name', $dataPengguna->GetName());
        // Session::put('level', '[]');
        Session::put('levelActive', $dataPengguna->GetLevel());
        Session::put('kodeFakultas', $dataPengguna->GetFaculty());
        Session::put('kodeProdi', $dataPengguna->GetProgramStudy());    
        Session::put('jafung', $dataPengguna->GetPosition());
        Session::put('struktural', $dataPengguna->GetStructural());
        Session::put('unit_kerja', $dataPengguna->GetUnit());
        dd(Session::all());
    }
}