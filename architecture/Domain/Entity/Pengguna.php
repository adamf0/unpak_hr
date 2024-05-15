<?php
namespace Architecture\Domain\Entity;

use Illuminate\Support\Facades\Hash;

class Pengguna{
    public function __construct(public $id=null, public $username, public $password=null, public $name=null, public $faculty=null, public $programStudy=null, public $position=null, public $level=null, public $active=true){}

    public function GetId(){
        return $this->id;
    }
    public function GetUsername(){
        return $this->username;
    }
    public function GetPassword(){
        return $this->password;
    }
    public function GetLevel(){
        return $this->level;
    }
    public function GetName(){
        return $this->name;
    }
    public function GetFaculty(){
        return $this->faculty;
    }
    public function GetProgramStudy(){
        return $this->programStudy;
    }
    public function GetPosition(){
        return $this->position;
    }
    public function IsActive(){
        return $this->active;
    }
    public function ChangeFaculty($kode_fak){
        $this->faculty = $kode_fak;
    }
    public function AuthenticationSystem($username,$password){
        return $this->username == $username && Hash::check($password,$this->password);
    }
    public function AuthenticationSimak($username,$password){
        return $this->username == $username && sha1(md5($password))==$this->password;
    }
    public function AuthenticationSimpeg($username,$password){
        return $this->username == $username && sha1($password)==$this->password;
    }
}