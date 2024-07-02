<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Abstractions\Pattern\OptionFileDefault;
use Architecture\Application\SPPD\Create\CreateAnggotaSPPDCommand;
use Architecture\Application\SPPD\Create\CreateSPPDCommand;
use Architecture\Application\SPPD\Create\CreateSPPDLaporanFileCommand;
use Architecture\Application\SPPD\Delete\DeleteAllAnggotaSPPDCommand;
use Architecture\Application\SPPD\Delete\DeleteAllSPPDLaporanFileCommand;
use Architecture\Application\SPPD\Delete\DeleteSPPDCommand;
use Architecture\Application\SPPD\FirstData\GetSPPDQuery;
use Architecture\Application\SPPD\Update\ApprovalSPPDCommand;
use Architecture\Application\SPPD\Update\UpdateSPPDCommand;
use Architecture\Application\SPPD\Update\UpdateSPPDLaporanCommand;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\DosenReferensi;
use Architecture\Domain\Entity\FolderX;
use Architecture\Domain\Entity\JenisSPPDReferensi;
use Architecture\Domain\Entity\PegawaiReferensi;
use Architecture\Domain\Entity\SPPDReferensi;
use Architecture\Domain\Enum\TypeNotif;
use Architecture\Domain\RuleValidationRequest\SPPD\CreateSPPDRuleReq;
use Architecture\Domain\RuleValidationRequest\SPPD\DeleteSPPDRuleReq;
use Architecture\Domain\RuleValidationRequest\SPPD\UpdateSPPDRuleReq;
use Architecture\Domain\Structural\AnggotaAdapter;
use Architecture\Domain\Structural\ListContext;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Persistance\ORM\SPPD;
use Architecture\External\Port\ExportSPPDXls;
use Architecture\External\Port\FileSystem;
use Architecture\External\Port\PdfX;
use Architecture\Shared\Creational\FileManager;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SPPDController extends Controller
{
    public $disk = "dokumen_laporan_sppd";
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {
    }

    public function index($type = null)
    {
        return view('sppd.index', ['type' => $type, 'verifikasi' => Session::get('levelActive') == "sdm"]);
    }
    public function verifikasi()
    {
        return view('sppd.index', ['type' => null, 'verifikasi' => true]);
    }

    public function create()
    {
        return view('sppd.create');
    }
    public function store(Request $request)
    {
        try {
            $validator      = validator($request->all(), CreateSPPDRuleReq::create());

            if (count($validator->errors())) {
                return redirect()->route('sppd.create')->withInput()->withErrors($validator->errors()->toArray());
            }

            DB::beginTransaction();
            $sppd = $this->commandBus->dispatch(new CreateSPPDCommand(
                Creator::buildDosen(DosenReferensi::make(Session::get('nidn'))),
                Creator::buildPegawai(PegawaiReferensi::make(Session::get('nip'))),
                Creator::buildJenisSPPD(JenisSppdReferensi::make(
                    $request->get('jenis_sppd')
                )),
                new Date($request->get('tanggal_berangkat')),
                new Date($request->get('tanggal_kembali')),
                $request->get('tujuan'),
                $request->get('keterangan'),
                $request->get('sarana_transportasi'),
                $request->has("verifikasi")? Creator::buildPegawai(PegawaiReferensi::make($request->get("verifikasi"))):null,
                "menunggu"
            ));

            foreach ($request->get('anggota') as $anggota) {
                $anggota = (object) $anggota;
                $this->commandBus->dispatch(new CreateAnggotaSPPDCommand(
                    Creator::buildSPPD(SPPDReferensi::make($sppd->id)),
                    Creator::buildDosen(DosenReferensi::make($anggota->nidn)),
                    Creator::buildPegawai(PegawaiReferensi::make($anggota->nip)),
                ));
            }
            DB::commit();
            Session::flash(TypeNotif::Create->val(), "berhasil tambah data");

            return redirect()->route('sppd.index2',['type'=>Session::get('levelActive')=="pegawai"? "tendik":"dosen"]);
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('sppd.create')->withInput();
        }
    }
    public function edit($id)
    {
        try {
            $SPPD = $this->queryBus->ask(new GetSPPDQuery($id));
            $list = new ListContext();

            return view('sppd.edit', [
                "SPPD" => $SPPD,
                "listAnggota" => $list->setAdapter(new AnggotaAdapter())->getList($SPPD->GetListAnggota()),
            ]);
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('sppd.index2',['type'=>Session::get('levelActive')=="pegawai"? "tendik":"dosen"]);
        }
    }
    public function update(Request $request)
    {
        try {
            $validator      = validator($request->all(), UpdateSPPDRuleReq::create());

            if (count($validator->errors())) {
                return redirect()->route('sppd.edit', ["id" => $request->get("id")])->withInput()->withErrors($validator->errors()->toArray());
            }

            DB::beginTransaction();
            $sppd = $this->queryBus->ask(new GetSPPDQuery($request->get("id")));
            $sppd = $this->commandBus->dispatch(new UpdateSPPDCommand(
                $request->get('id'),
                Creator::buildDosen(DosenReferensi::make(Session::get('nidn'))),
                Creator::buildPegawai(PegawaiReferensi::make(Session::get('nip'))),
                Creator::buildJenisSPPD(JenisSPPDReferensi::make(
                    $request->get('jenis_sppd')
                )),
                new Date($request->get('tanggal_berangkat')),
                new Date($request->get('tanggal_kembali')),
                $request->get('tujuan'),
                $request->get('keterangan'),
                $request->get('sarana_transportasi'),
                $request->has("verifikasi")? Creator::buildPegawai(PegawaiReferensi::make($request->get("verifikasi"))):null,
                $sppd->GetStatus()
            ));

            $this->commandBus->dispatch(new DeleteAllAnggotaSPPDCommand($sppd->id));

            foreach ($request->get('anggota') as $anggota) {
                $anggota = (object) $anggota;
                $this->commandBus->dispatch(new CreateAnggotaSPPDCommand(
                    Creator::buildSPPD(SPPDReferensi::make($sppd->id)),
                    Creator::buildDosen(DosenReferensi::make($anggota->nidn)),
                    Creator::buildPegawai(PegawaiReferensi::make($anggota->nip)),
                ));
            }
            DB::commit();
            Session::flash(TypeNotif::Update->val(), "berhasil ubah data");

            return redirect()->route('sppd.index2',['type'=>Session::get('levelActive')=="pegawai"? "tendik":"dosen"]);
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('sppd.edit', ["id" => $request->get('id')])->withInput();
        }
    }
    public function delete($id)
    {
        $request = request()->merge(["id" => $id]);
        try {
            $validator      = validator($request->all(), DeleteSPPDRuleReq::create());

            if (count($validator->errors())) {
                return redirect()->route('sppd.index')->withErrors($validator->errors()->toArray());
            }

            $this->commandBus->dispatch(new DeleteSPPDCommand($id));
            Session::flash(TypeNotif::Create->val(), "berhasil hapus data");

            return redirect()->route('sppd.index2',['type'=>Session::get('levelActive')=="pegawai"? "tendik":"dosen"]);
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('sppd.index2',['type'=>Session::get('levelActive')=="pegawai"? "tendik":"dosen"]);
        }
    }

    public function approval($id,$type)
    {
        $sppd = $this->queryBus->ask(new GetSPPDQuery($id));
        $redirect = match(true){
            Session::get('levelActive')=="sdm" && !is_null($sppd->GetDosen()) => redirect()->route('sppd.index2',['type'=>'dosen']),
            Session::get('levelActive')=="sdm" && !is_null($sppd->GetPegawai()) => redirect()->route('sppd.index2',['type'=>'tendik']),
            in_array(Session::get('levelActive'), ["dosen","pegawai"]) => redirect()->route('sppd.index2',['type'=>'verifikasi']),
            default=>redirect()->route('sppd.index'),
        };

        try {
            if (empty($id)) throw new Exception("invalid reject sppd");
            // if (!in_array($level, ['sdm', 'warek'])) throw new Exception("selain SDM dan Warek tidak dapat approval sppd");

            $this->commandBus->dispatch(new ApprovalSPPDCommand(
                $id, 
                $type=="warek"? "menunggu verifikasi sdm":"terima sdm", 
                null,
                $type=="warek"? null:Session::get('id'))
            );

            Session::flash(TypeNotif::Create->val(), "berhasil terima SPPD");
            return $redirect;
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return $redirect;
        }
    }

    public function laporan($id)
    {
        try {
            $SPPD = $this->queryBus->ask(new GetSPPDQuery($id));
            $list = new ListContext();

            return view('sppd.laporan', [
                "SPPD" => $SPPD,
                "listAnggota" => $list->setAdapter(new AnggotaAdapter())->getList($SPPD->GetListAnggota()),
            ]);
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('sppd.index2',['type'=>Session::get('levelActive')=="pegawai"? "tendik":"dosen"]);
        }
    }

    public function save_laporan(Request $request)
    {
        try {
            // $validator      = validator($request->all(), UpdateSPPDRuleReq::create());

            // if(count($validator->errors())){
            //     return redirect()->route('sppd.edit',["id"=>$request->get("id")])->withInput()->withErrors($validator->errors()->toArray());    
            // } 

            DB::beginTransaction();
            $SPPD = $this->queryBus->ask(new GetSPPDQuery($request->get('id')));
            $this->commandBus->dispatch(new UpdateSPPDLaporanCommand(
                $request->get('id'),
                $request->get('intisari'),
                $request->get('kontribusi_ufu'),
                $request->get('rencana_tindak_lanjut'),
                $request->get('rencana_waktu_tindak_lanjut'),
            ));

            if ($request->has("undangan") && $request->file("undangan") != null) {
                $this->handleFileUpload($request, 'undangan', $SPPD->GetUndangan());
            }
            
            if ($request->has("foto_kegiatan") && $request->file("foto_kegiatan") != null) {
                $this->handleFileUpload($request, 'foto_kegiatan', $SPPD->GetFotoKegiatan());
            }

            DB::commit();
            Session::flash(TypeNotif::Update->val(), "berhasil update laporan");

            return redirect()->route('sppd.index2',['type'=>Session::get('levelActive')=="pegawai"? "tendik":"dosen"]);
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('sppd.laporan', ["id" => $request->get('id')])->withInput();
        }
    }

    private function handleFileUpload($request, $type, $existingFiles) {
        $files = [];
        $this->commandBus->dispatch(new DeleteAllSPPDLaporanFileCommand(
            $request->get('id'),
            $type
        ));
    
        $existingFiles = $existingFiles ?? collect([]);
        foreach ($existingFiles as $file) {
            if (Storage::disk($this->disk)->exists($file->file)) {
                Storage::disk($this->disk)->delete($file->file);
            }
        }
    
        foreach ($request->file($type) as $file) {
            $fileSystem = new FileSystem(new OptionFileDefault($file, "dokumen_laporan_sppd"));
            $files[] = ["id_sppd" => $request->get('id'), "file" => $fileSystem->storeFileWithReplaceFileAndReturnFileLocation(), "type" => $type];
        }
        
        $this->commandBus->dispatch(new CreateSPPDLaporanFileCommand($files));
    }

    public function export(Request $request)
    {
        try {
            $id             = $request->has('id') ? $request->query('id') : null;
            $nama           = $request->has('nama') ? $request->query('nama') : null;
            $type           = $request->has('type') ? $request->query('type') : null;
            $jenis_sppd     = $request->has('jenis_sppd') ? $request->query('jenis_sppd') : null;
            $status         = $request->has('status') ? $request->query('status') : null;
            $tanggal_berangkat  = $request->has('tanggal_berangkat') ? $request->query('tanggal_berangkat') : null;
            $tanggal_kembali  = $request->has('tanggal_kembali') ? $request->query('tanggal_kembali') : null;
            $type_export    = $request->has('type_export') ? $request->query('type_export') : null;

            $file_name = "sppd";
            $sppd = SPPD::with(['SDM', 'EPribadiRemote', 'Dosen', 'Pegawai', 'JenisSPPD', 'Anggota', 'Anggota.Dosen', 'Anggota.Pegawai','FileLaporan']);

            if (is_null($type_export)) {
                throw new Exception("belum pilih cetak sebagai apa");
            }

            if ($id) {
                $sppd->where('id', $id);
            }
            if ($type == "dosen" && !is_null($nama)) {
                $sppd->where('nidn', $nama);
                $file_name = $file_name . "_$nama";
            } else if ($type == "dosen" && is_null($nama)) {
                $sppd->whereNotNull('nidn');
                $file_name = $file_name . "_semua-nama";
            } else if ($type == "tendik" && !is_null($nama)) {
                $sppd->where('nip', $nama);
                $file_name = $file_name . "_$nama";
            } else if ($type == "tendik" && is_null($nama)) {
                $sppd->whereNotNull('nip');
                $file_name = $file_name . "_semua-nama";
            }
            if ($jenis_sppd) {
                $sppd->where('id_jenis_sppd', $jenis_sppd);
                $file_name = $file_name . "_$jenis_sppd";
            }
            if ($status) {
                $sppd->where('status', $status);
                $file_name = $file_name . "_$status";
            }
            if ($tanggal_berangkat && is_null($tanggal_kembali)) {
                $sppd->where('tanggal_berangkat', $tanggal_berangkat);
                $file_name = $file_name . "_$tanggal_berangkat";
            } else if ($tanggal_kembali && is_null($tanggal_berangkat)) {
                $sppd->where('tanggal_kembali', $tanggal_kembali);
                $file_name = $file_name . "_$tanggal_kembali";
            } else if ($tanggal_berangkat && $tanggal_kembali) {
                $sppd->whereBetween('tanggal_berangkat', [$tanggal_berangkat, $tanggal_kembali])
                    ->whereBetween('tanggal_kembali', [$tanggal_berangkat, $tanggal_kembali]);

                $file_name = $file_name . "_$tanggal_berangkat-$tanggal_kembali";
            }
            $list_sppd = $sppd->get();
            $list_sppd = $list_sppd->map(function ($row) {
                $tanggal_berangkat = empty($row->tanggal_berangkat) ? null : Carbon::parse($row->tanggal_berangkat)->setTimezone('Asia/Jakarta');
                $tanggal_kembali = empty($row->tanggal_kembali) ? null : Carbon::parse($row->tanggal_kembali)->setTimezone('Asia/Jakarta');

                $row->AnggotaFlat = (!empty($tanggal_berangkat) && !empty($tanggal_kembali)) ? $row->Anggota?->reduce(function ($carry, $item) use ($tanggal_berangkat, $tanggal_kembali) {
                    $lama_hari = $tanggal_berangkat==$tanggal_kembali? 1:$tanggal_kembali->diff($tanggal_berangkat)->days;
                    $nama = match (true) {
                        !empty($item->Dosen) => $item->Dosen->nama_dosen,
                        !empty($item->Pegawai) => $item->Pegawai->nama,
                        default => "NA"
                    };
                    $kodePengenal = match (true) {
                        !empty($item->Dosen) => $item->Dosen->NIDN,
                        !empty($item->Pegawai) => $item->Pegawai->nip,
                        default => "NA"
                    };

                    for ($i = 0; $i < $lama_hari; $i++) {
                        $carry[] = (object)[
                            "nama" => $nama ?? "NA",
                            "kodePengenal" => $kodePengenal,
                            "tanggal" => Carbon::parse($tanggal_berangkat->format("Y-m-d"))->setTimezone('Asia/Jakarta')->addDays($i)->format("d F Y"),
                        ];
                    }
                    return $carry;
                }) ?? [] : [];

                return $row;
            });

            if ($type_export == "pdf") {
                $file = PdfX::From(
                    "template.export_sppd",
                    [
                        "list_sppd" => $list_sppd,
                    ],
                    FolderX::FromPath(public_path('export/pdf')),
                    "$file_name.pdf"
                );
                return FileManager::StreamFile($file);
            } else {
                $list_sppd = $list_sppd->reduce(function ($carry, $item) {
                    $list_anggota = array_unique(array_reduce($item->AnggotaFlat, function ($carry, $it) {
                        $carry[] = $it->nama . '-' . $it->kodePengenal;
                        return $carry;
                    }, []));

                    dd($item->EPribadiRemote);
                    $carry[] = [
                        'verifikasi' => match (true) {
                            !is_null($item->EPribadiRemote) => $item->EPribadiRemote->nama,
                            default => "Tidak diketahui"
                        },
                        'kode_verifikasi' => match (true) {
                            !is_null($item->EPribadiRemote) => $item->EPribadiRemote->nip,
                            default => "Tidak diketahui"
                        },
                        'nama' => match (true) {
                            !is_null($item->Dosen) && is_null($item->Pegawai) => $item->Dosen->nama_dosen,
                            is_null($item->Dosen) && !is_null($item->Pegawai) => $item->Pegawai->nama,
                            default => "NA"
                        },
                        'tujuan' => $item->tujuan,
                        'jenis_sppd' => $item->JenisSPPD?->nama,
                        'tanggal_berangkat' => Carbon::parse($item->tanggal_berangkat)->setTimezone('Asia/Jakarta')->format("d F Y"),
                        'tanggal_kembali' => Carbon::parse($item->tanggal_kembali)->setTimezone('Asia/Jakarta')->format("d F Y"),
                        'keterangan' => $item->keterangan,
                        'anggota' => implode("\n", $list_anggota)
                    ];

                    return $carry;
                });
                return Excel::download(new ExportSPPDXls(collect($list_sppd), ['nama', 'tujuan', 'jenis sppd', 'tanggal berangkat', 'tanggal kembali', 'keterangan', 'anggota']), "$file_name.xlsx");
            }
        } catch (Exception $e) {
            throw $e;
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return empty($type) ? redirect()->route('sppd.index') : redirect()->route('sppd.index2', ['type' => $type]);
        }
    }
}
