<?php
namespace Architecture\Shared\Behavioral;

use Architecture\Domain\Enum\TypeStatusAnggota;

class StatusWaitingAsOwnData extends MappingHtmlOrmStateInterface{
    public function __construct(public $item){}
    
    public function handle() {
        $memberTerima = $this->item->GetListAnggota()->filter(fn($row)=>$row->GetStatus()==TypeStatusAnggota::Diterima)->values();
        $memberTolak = $this->item->GetListAnggota()->filter(fn($row)=>$row->GetStatus()==TypeStatusAnggota::Ditolak)->values();
        $memberMenunggu = $this->item->GetListAnggota()->filter(fn($row)=>$row->GetStatus()==TypeStatusAnggota::Default)->values();

        $memberTerima = $this->item->GetListAnggota()->filter(fn($row)=>$row->GetStatus()==TypeStatusAnggota::Diterima)->values();
        $memberTolak = $this->item->GetListAnggota()->filter(fn($row)=>$row->GetStatus()==TypeStatusAnggota::Ditolak)->values();
        $memberMenunggu = $this->item->GetListAnggota()->filter(fn($row)=>$row->GetStatus()==TypeStatusAnggota::Default)->values();

        $listNamaTerima = $memberTerima->reduce(function ($listTerima, $anggota) {
            if($anggota->GetStatus()==TypeStatusAnggota::Diterima)
                $listTerima[] = $anggota->GetNama();
            return $listTerima;
        }, []);
        $namaTerima = ConjunctionContext::merge(match (count($listNamaTerima)) {
            0 => new ZeroElementStrategy(),
            1 => new OneElementStrategy(),
            2 => new TwoElementStrategy(),
            default => new ManyElementStrategy(),
        }, $listNamaTerima);

        $listNamaTolak = $memberTolak->reduce(function ($listTerima, $anggota) {
            if($anggota->GetStatus()==TypeStatusAnggota::Ditolak)
                $listTerima[] = $anggota->GetNama();
            return $listTerima;
        }, []);
        $namaTolak = ConjunctionContext::merge(match (count($listNamaTolak)) {
            0 => new ZeroElementStrategy(),
            1 => new OneElementStrategy(),
            2 => new TwoElementStrategy(),
            default => new ManyElementStrategy(),
        }, $listNamaTolak);
        
        $listNamaMenunggu = $memberMenunggu->reduce(function ($listTerima, $anggota) {
            if($anggota->GetStatus()==TypeStatusAnggota::Default)
                $listTerima[] = $anggota->GetNama();
            return $listTerima;
        }, []);
        $namaMenunggu = ConjunctionContext::merge(match (count($listNamaMenunggu)) {
            0 => new ZeroElementStrategy(),
            1 => new OneElementStrategy(),
            2 => new TwoElementStrategy(),
            default => new ManyElementStrategy(),
        }, $listNamaMenunggu);

        $this->output = "
        <br>
        <span data-bs-toggle='tooltip' title='' data-bs-original-title='".$namaTerima."'>
            <span class='badge rounded-pill bg-success'>Terima:".$memberTerima->count()."</span>
        </span>
        <span data-bs-toggle='tooltip' title='' data-bs-original-title='".$namaTolak."'>
            <span class='badge rounded-pill bg-danger'>Tolak:".$memberTolak->count()."</span>
        </span>
        <span data-bs-toggle='tooltip' title='' data-bs-original-title='".$namaMenunggu."'>
            <span class='badge rounded-pill bg-warning text-black'>Menunggu:".$memberMenunggu->count()."</span>
        </span>
        ";

        return $this;
    }
}