<?php
namespace Architecture\Shared\Behavioral;

use Architecture\Domain\Enum\TypeStatusAdministrasi;

class StatusAdministrasiIsAccept implements RuleRenderHtmlStartegy{
    public function __construct(){} 
    public function rule(?TypeStatusAdministrasi $data=null){
        return $data!=TypeStatusAdministrasi::ADA_DAN_SESUAI;
    }
}