<?php

namespace Architecture\External\Port;

use Architecture\Domain\Entity\FolderX;
use Architecture\Shared\Export;
use Architecture\Shared\Stream;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class PdfX implements Export, Stream
{
    private $compile;

    function __construct(private $view, private $datas = [],private FolderX $folder,private $file, private $custom_width=false){
        if($custom_width){
            $this->compile = Pdf::setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                // 'enable_remote' => true, 
                'chroot' => public_path('assets')
            ])
            ->setPaper(array(0,0,2048,1024))
            ->loadHTML(
                view($view)->with($datas)->render()
            );
        } else{
            $this->compile = Pdf::setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                // 'enable_remote' => true, 
                'chroot' => public_path('assets')
            ])
            ->setPaper('legal')
            ->loadHTML(
                view($view)->with($datas)->render()
            );
        }
    }

    public static function From($view, $datas = [],FolderX $folder,$file,$custom_width=false){
        match (true) {
            is_null($view)     => throw new Exception("view export can't be null"),
            is_null($folder)   => throw new Exception("destination folder export can't be null"),
            is_null($file)     => throw new Exception("file name export can't be null"),
            default            => false
       };

       return new self($view, $datas,$folder,$file,$custom_width);
    }
    public function export()
    {
        return $this->compile->download($this->file);
    }
    public function stream()
    {
        // return view($this->view)->with($this->datas)->render();
        return $this->compile->stream();
    }
}