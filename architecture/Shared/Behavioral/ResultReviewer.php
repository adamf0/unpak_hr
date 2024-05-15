<?php
namespace Architecture\Shared\Behavioral;

use Architecture\Domain\Enum\FormatDate;
use Architecture\Domain\Enum\TypeStatusPengajuan;
use Architecture\Domain\ValueObject\Date;
use Architecture\Shared\Structural\Table;
use Architecture\Shared\Structural\TableCell;
use Architecture\Shared\Structural\TableRow;
use Exception;

class ResultReviewer extends MappingHtmlOrmStateInterface{
    public function __construct(public $item, public $level, public $type="internal", public $render="table"){}
    
    public function handle() {
        if($this->item->GetListSubstansi()->count()==0) $this->output .= null;
        else {
            if($this->render=="table"){
                $totalData  = $this->item->GetListSubstansi();
                $table      = new Table();

                foreach($this->item->GetListSubstansi() as $substansi){
                    $namaReviewer       = match(true){
                        !is_null($substansi->GetReviewerInternal()) && is_null($substansi->GetReviewerExternal())                                   => $substansi->GetReviewerInternal()->GetNama(),
                        is_null($substansi->GetReviewerInternal()) && !is_null($substansi->GetReviewerExternal())                                   => $substansi->GetReviewerExternal()->GetName(),
                        default => "N/A"
                    };
                    $tanggalDitugaskan  = match(true){
                        is_null($substansi->GetTanggalMulai()->val()) || 
                        is_null($substansi->GetTanggalBerakhir()->val()) || 
                        $substansi->GetTanggalBerakhir()->isLess($substansi->GetTanggalMulai())                                                     => "",
                        $substansi->GetTanggalBerakhir()->differentDays($substansi->GetTanggalMulai())==0                                           => "ditugaskan pada tanggal ".$substansi->GetTanggalMulai()->toFormat(FormatDate::LDFY),
                        default                                                                                                                     => $tanggalDitugaskan = "ditugaskan pada tanggal ".$substansi->GetTanggalMulai()->toFormat(FormatDate::LDFY)." - ".$substansi->GetTanggalBerakhir()->toFormat(FormatDate::LDFY)
                    };
    
                    $date   = new Date();
                    $label  = match(true){
                        is_null($substansi->GetTanggalMulai()->val()) || 
                        is_null($substansi->GetTanggalBerakhir()->val()) || 
                        $substansi->GetTanggalBerakhir()->isLess($substansi->GetTanggalMulai())                                                     => "bg-secondary text-black",
                        
                        !$substansi->IsDoneInput() && 
                        (
                            $substansi->GetTanggalBerakhir()->differentDays($substansi->GetTanggalMulai())==0 || 
                            $date->now()->inRangeDate($substansi->GetTanggalMulai(),$substansi->GetTanggalBerakhir())
                        )                                                                                                                           => "bg-warning text-black",
                        $substansi->IsDoneInput() || 
                        (
                            $substansi->GetTanggalBerakhir()->differentDays($substansi->GetTanggalMulai())==0 || 
                            $date->now()->inRangeDate($substansi->GetTanggalMulai(),$substansi->GetTanggalBerakhir())
                        )                                                                                                                           => "bg-success",
                        ($substansi->IsDoneInput() || !$substansi->IsDoneInput()) && 
                            !$date->now()->inRangeDate($substansi->GetTanggalMulai(),$substansi->GetTanggalBerakhir())                              => "bg-danger",
                        default                                                                                                                     => "bg-secondary text-black"
                    };
    
                    $row = new TableRow();
                    $row->add(
                        new TableCell("<span data-bs-toggle='tooltip' title='' data-bs-original-title='$tanggalDitugaskan'>
                                            <span class='badge rounded-pill $label d-grid'>$namaReviewer</span>
                                       </span>")
                    );

                    if(
                        $this->level=="lppm" && 
                        (
                            ($this->item->GetStatus() == TypeStatusPengajuan::MENUNGGU_PILIH_REVIEWER && $totalData==0) || 
                            in_array($this->item->GetStatus(),[TypeStatusPengajuan::MENUNGGU_REVIEWER,TypeStatusPengajuan::TERIMA])
                        )
                    ){
                        $row->add(
                            new TableCell("<a href='".route("api.".($this->type=="internal"? "InternalReviewer":"PKMReviewer").".removeAssign",["id"=>$substansi->GetId(), ($this->type=="internal"? "idPDP":"idPKM")=>$this->item->GetId()])."' class='btn btn-sm btn-danger btn-delete-reviewer'><i class='bi bi-trash'></i></a>")
                        );
                    }
                    $table->add($row);
                }

                $this->output .= $table->render();
            } else{
                throw new Exception("masih pengembangan");
            }
        }
        return $this;
    }
}