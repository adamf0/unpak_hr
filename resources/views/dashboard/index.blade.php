@extends('template.index')
 
@section('page-title')
    <x-page-title title="Dashboard">
        <!-- <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Lembaga Akreditasi</li>
            </ol>
        </nav> -->
    </x-page-title>
@stop

@section('content')
<style>
    .absen_done{
        & img{
            width: 100%;
        }
        & h4{
            font-size: 1.5rem;
            text-align: center;
            font-weight: bold;
        }
    }
    .scrollme {
        overflow-x: auto;
    }
    .grid-card{
        display: grid;
        grid-template-rows: 2;
        grid-template-columns: minmax(min-content, 1fr) minmax(min-content, 1fr);
    }
    .fc .fc-button{
        padding: clamp(.1em,.09em + 2vmax,.4em) clamp(.2em,.2em + 20vmax,.65em) !important;
    }
    .legend-calendar{
        & .col p{
            font-size: clamp(0.6rem,0.6rem + 2vmax,1.2rem);
        }
    }
    .fc .fc-view-harness{
        min-height: 75vmax !important;
    }
    .fc-toolbar-title{
        font-size: clamp(0.8rem, 0.8rem + 40vmax, 1.2rem);
    }
    .fc-header-toolbar .fc-toolbar {
        flex-wrap: wrap;
    }
    .fc-toolbar-chunk {
        min-width: fit-content;
    }
    @media (max-width: 350px) {
        .grid-card{
            grid-template-rows: 1;
            grid-template-columns: minmax(min-content, 1fr);
        }       
    }
    @media (max-width: 470px) {
        .legend-calendar{
            flex-direction: column;
            gap: .5rem;
            & .col{
                display: inline-flex;
                /* align-items: center; */
                justify-content: flex-start;
                flex-wrap: wrap;
            }
        }       
    }
</style>
<div class="row">
    @if (Utility::hasUser())
    <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
        <div class="card info-card sales-card">
            <div class="card-body">
                <h5 class="card-title">PRESENSI</h5>
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex flex-row flex-wrap flex-grow-1 align-items-center placeholder-glow">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <img src="{{ Utility::loadAsset('assets/img/icon 32x32/icons8-persensi.png') }}" alt=""/>
                            </div>
                            <h6 class="mx-3 flex-grow-1 presensi_total"><span class="placeholder col-2"></span></h6>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="grid-card">
                            <div class="presensi_belum_absen placeholder-glow">
                                <span class="placeholder col-4"></span>
                            </div>
                            <div class="presensi_tidak_masuk placeholder-glow">
                                <span class="placeholder col-4"></span>
                            </div>
                            <div class="presensi_tepat_waktu placeholder-glow">
                                <span class="placeholder col-4"></span>
                            </div>
                            <div class="presensi_telat placeholder-glow">
                                <span class="placeholder col-4"></span>
                            </div>
                            <div class="presensi_r8jam placeholder-glow">
                                <span class="placeholder col-4"></span>
                            </div>
                            <div class="presensi_l8jam placeholder-glow">
                                <span class="placeholder col-4"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
    <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
        <div class="card info-card sales-card">
            <div class="card-body">
                <h5 class="card-title">CUTI</h5>
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex flex-row flex-wrap flex-grow-1 align-items-center placeholder-glow">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <img src="{{ Utility::loadAsset('assets/img/icon 32x32/icons8-cuti.png') }}" alt=""/>
                            </div>
                            <h6 class="mx-3 flex-grow-1 cuti_total"><span class="placeholder col-2"></span></h6>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="grid-card">
                            <div class="cuti_tolak placeholder-glow">
                                <span class="placeholder col-4"></span>
                            </div>
                            <div class="cuti_tunggu placeholder-glow">
                                <span class="placeholder col-4"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
        <div class="card info-card sales-card">
            <div class="card-body">
                <h5 class="card-title">IZIN</h5>
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex flex-row flex-wrap flex-grow-1 align-items-center placeholder-glow">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <img src="{{ Utility::loadAsset('assets/img/icon 32x32/icons8-izin.png') }}" alt=""/>
                            </div>
                            <h6 class="mx-3 flex-grow-1 izin_total"><span class="placeholder col-2"></span></h6>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="grid-card">
                            <div class="izin_tolak placeholder-glow">
                                <span class="placeholder col-4"></span>
                            </div>
                            <div class="izin_tunggu placeholder-glow">
                                <span class="placeholder col-4"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
        <div class="card info-card sales-card">
            <div class="card-body">
                <h5 class="card-title">SPPD</h5>
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex flex-row flex-wrap flex-grow-1 align-items-center placeholder-glow">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <img src="{{ Utility::loadAsset('assets/img/icon 32x32/icons8-sppd.png') }}" alt=""/>
                            </div>
                            <h6 class="mx-3 flex-grow-1 sppd_total"><span class="placeholder col-2"></span></h6>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="grid-card">
                            <div class="sppd_tolak placeholder-glow">
                                <span class="placeholder col-4"></span>
                            </div>
                            <div class="sppd_tunggu placeholder-glow">
                                <span class="placeholder col-4"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12">
        <div class="card info-card sales-card">
            <div class="card-body">
                <h5 class="card-title">Informasi</h5>
                <div class="row">
                    <div class="col-12">
                        <div class="grid-card">
                            <div>
                                <span class="text-success small pt-1 fw-bold">Masuk</span> 
                                <span class="text-muted small pt-2 ps-1 info_absen_masuk"><span class="placeholder col-2"></span></span>                            
                            </div>
                            <div>
                                <span class="text-secondary small pt-1 fw-bold">Catatan Telat</span> 
                                <span class="text-muted small pt-2 ps-1 info_absen_catatan_telat"><span class="placeholder col-2"></span></span>                            
                            </div>
                            <div>
                                <span class="text-success small pt-1 fw-bold">Keluar</span> 
                                <span class="text-muted small pt-2 ps-1 info_absen_keluar"><span class="placeholder col-2"></span></span>                            
                            </div>
                            <div>
                                <span class="text-secondary small pt-1 fw-bold">Catatan Pulang</span> 
                                <span class="text-muted small pt-2 ps-1 info_absen_catatan_pulang"><span class="placeholder col-2"></span></span>                            
                            </div>
                            <div>
                                <span class="text-success small pt-1 fw-bold">Telat</span> 
                                <span class="text-muted small pt-2 ps-1 info_absen_telat"><span class="placeholder col-2"></span></span>                            
                            </div>
                            <div>
                                <span class="text-success small pt-1 fw-bold">Jam kerja</span> 
                                <span class="text-muted small pt-2 ps-1 info_absen_jam_kerja"><span class="placeholder col-2"></span></span>                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="card info-card sales-card">
            <div class="card-body">
                <div class="row absen_form" style="display: none;">
                    <div class="col-12">
                        <label class="absen_message">-</label>
                        <textarea name="keterangan" class="form-control absen_keterangan" style="min-height: 45vmin;" placeholder="masukkan keterangan"></textarea>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-warning absen_submit">Presensi Masuk</button>
                    </div>
                </div>
                <div class="absen_done" style="display: none;">
                    <img src="{{ Utility::loadAsset('assets/img/pulang_hrportal.jpg') }}" alt="sudah pulang">
                    <!-- <h4>Anda udah Pulang</h4> -->
                </div>
                <div class="absen_libur" style="display: none;">
                    <img src="{{ Utility::loadAsset('assets/img/libur_hrportal.jpg') }}" style="width:100%" alt="libur">
                    <!-- <h4 class="text-center">Tidak ada absen untuk hari libur</h4> -->
                </div>
                <div class="absen_kerja" style="display: none;">
                    <img src="{{ Utility::loadAsset('assets/img/work_hrportal.jpg') }}" style="width:100%" alt="libur">
                    <!-- <h4 class="text-center">Tidak ada absen untuk hari libur</h4> -->
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="card info-card sales-card">
            <div class="card-body">
                <h5 class="card-title">Ketentuan</h5>
                <div class="row">
                    <div class="col-12">
                        <ol class="list-group list-group-numbered">
                            <li class="list-group-item">Jam Masuk & Jam Pulang WAJIB terhubung/Koneksi JARINGAN WIFI/LAN UNIVERSITAS PAKUAN Seperti (UNPAK Access / Eduroam)</li>
                            <li class="list-group-item">Jam Masuk &gt;  08.00 WIB harus isi keterangan keterlambatan</li>
                            <li class="list-group-item">Jam Pulang  &le; 15.00 WIB atau &le;14.00 WIB (Jumat) harus isi Keterangan Pulang cepat</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card info-card sales-card">
            <div class="card-body">
                <h5 class="card-title">Calendar</h5>
                <div class="row">
                    <div class="loading-calendar d-flex justify-content-center align-items-center" style="position: absolute;width: -webkit-fill-available;height: -webkit-fill-available;background: #b3b3b3;z-index: 2;opacity: 0.7;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>    
                    </div>
                    <div id="calendar" style="z-index: 1;"></div>
                </div>
                <div class="row legend-calendar">
                    <div class="col">
                        <span class="badge bg-danger rounded-pill">&nbsp;</span>&nbsp;<p>TIDAK MASUK / LIBUR</p>
                    </div>
                    <div class="col">
                        <span class="badge bg-warning rounded-pill">&nbsp;</span>&nbsp;<p>CUTI</p>
                    </div>
                    <div class="col">
                        <span class="badge bg-primary rounded-pill">&nbsp;</span>&nbsp;<p>IZIN</p>
                    </div>
                    <div class="col">
                        <span class="badge bg-info rounded-pill">&nbsp;</span>&nbsp;<p>SPPD</p>
                    </div>
                    <div class="col">
                        <span class="badge bg-success rounded-pill">&nbsp;</span>&nbsp;<p>MASUK</p>
                    </div>
                    <div class="col">
                        <span class="badge bg-black rounded-pill">&nbsp;</span>&nbsp;<p>TELAT MASUK / PULANG CEPAT</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="card info-card sales-card">
            <div class="card-body">
                <h5 class="card-title">DAFTAR DOSEN AKTIF CUTI</h5>
                <small class="text-primary">(hari ini)</small>
                <div class="row col-12 table">
                    <table id="tbCutiDosen" class="table-stripped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Tujuan</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">DAFTAR TENDIK AKTIF CUTI</h5>
                <small class="text-primary">(hari ini)</small>
                <div class="row col-12 table">
                    <table id="tbCutiPegawai" class="table-stripped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Tujuan</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@php
    $refid = match(true){
        Session::get('nidn')!=null => Session::get('nidn'),
        Session::get('nip')!=null => Session::get('nip'),
        default => '-',
    };
    $type = match(true){
        Session::get('nidn')!=null => 'nidn',
        Session::get('nip')!=null => 'nip',
        default => '-',
    };
@endphp
@stop

@push('scripts')
    <script>
        $(document).ready(function () {
            $("#tb").DataTable();
        });
    </script>
    <script type="text/javascript" src="{{ Utility::loadAsset('my.js') }}"></script>
    <script>
        $(document).ready(function () {
            // $('#tb').DataTable();

            const nidn = `{{Session::get('nidn')}}`
            const nip = `{{Session::get('nip')}}`
            const level = `{{Session::get('levelActive')}}`

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                header: {
                left: 'dayGridMonth,timeGridWeek,timeGridDay',
                center: 'title',
                right: 'listDay,listWeek,month,prevYear,prev,next,nextYear' 
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: `{{ route('api.kalendar.index', ['tahun' => date('Y'), 'format' => 'full-calendar']) }}?level=${level}&nidn=${nidn}&nip=${nip}`,
                        method: 'GET',
                        beforeSend:function(){
                            $('.loading-calendar').show()
                        },
                        success: function(response) {
                            var events = response.data.map(function(eventData) {
                                return {
                                    id: eventData.id,
                                    title: eventData.title,
                                    start: eventData.start,
                                    end: eventData.end,
                                    description: eventData.description,
                                    backgroundColor: eventData.backgroundColor,
                                    borderColor: eventData.borderColor,
                                };
                            });
                            console.log(events)
                            $('.loading-calendar').attr('style', 'display: none !important;');
                            successCallback(events);
                        },
                        error: function(xhr, status, error) {
                            $('.loading-calendar').attr('style', 'display: none !important;');
                            failureCallback(error);
                        }
                    });
                }
            });
            calendar.render();

            const refAbsenForm = '.absen_form';
            const refAbsenMessage = '.absen_message';
            const refAbsenSubmit = '.absen_submit';
            const refAbsenKeterangan = '.absen_keterangan'
            const refAbsenDone = '.absen_done';
            const refInfoAbsenMasuk = '.info_absen_masuk'
            const refInfoAbsenCatatanTelat = '.info_absen_catatan_telat'
            const refInfoAbsenKeluar = '.info_absen_keluar'
            const refInfoAbsenTelat = '.info_absen_telat'
            const refInfoAbsenCatatanPulang = '.info_absen_catatan_pulang'
            const refInfoAbsenJamKerja = '.info_absen_jam_kerja'
            const refAbsenLibur = '.absen_libur';
            const refAbsenKerja = '.absen_kerja';

            const refPresensiTotal = '.presensi_total'
            const refPresensiTidakMasuk = '.presensi_tidak_masuk'
            const refPresensiBelumAbsen = '.presensi_belum_absen'
            const refPresensiTepatWaktu = '.presensi_tepat_waktu'
            const refPresensiTelat = '.presensi_telat'
            const refPresensiR8 = '.presensi_r8jam'
            const refPresensiL8 = '.presensi_l8jam'

            const refCutiTotal = '.cuti_total'
            const refCutiTolak = '.cuti_tolak'
            const refCutiTunggu = '.cuti_tunggu'

            const refIzinTotal = '.izin_total'
            const refIzinTolak = '.izin_tolak'
            const refIzinTunggu = '.izin_tunggu'

            const refSPPDTotal = '.sppd_total'
            const refSPPDTolak = '.sppd_tolak'
            const refSPPDTunggu = '.sppd_tunggu'
            
            const getCurrentTime = () => {
                return moment().tz('Asia/Jakarta')
            }
            String.prototype.isEmpty = function() {
                return (this.length === 0 || !this.trim());
            };

            const dateNow = getCurrentTime().format('YYYY-MM-DD');
            const timeAbsenString = "08:00:00"; 
            const timeAbsen = parseInt(timeAbsenString.split(":")[0]);
            var absenMasuk = "{{$presensi?->GetAbsenMasuk()?->toFormat(FormatDate::YMDHIS)}}";
            var absenKeluar = "{{$presensi?->GetAbsenKeluar()?->toFormat(FormatDate::YMDHIS)}}";
            var catatanTelat = "{{!is_null($presensi?->GetAbsenMasuk())? $presensi?->GetCatatanTelat():''}}";
            var catatanPulang = "{{!is_null($presensi?->GetAbsenKeluar())? $presensi?->GetCatatanPulang():''}}";

            function loadInfo(){
                $.ajax({
                    url: "{{ route('api.infoDashboard.index', ['type' => $type, 'id' => $refid]) }}",
                    method: 'GET',
                    success: function(response) {
                        setTimeout(function(){
                            var data = response.data
                            $(refPresensiTotal).html(data?.presensi?.total??0)
                            $(refPresensiTidakMasuk).html(`
                                <span class="text-danger small pt-1 fw-bold">${data?.presensi?.tidak_masuk??0}</span> 
                                <span class="text-muted small pt-2 ps-1">Tidak Masuk</span>
                            `)
                            $(refPresensiBelumAbsen).html(`
                                <span class="text-danger small pt-1 fw-bold">${data?.presensi?.belum_absen??0}</span> 
                                <span class="text-muted small pt-2 ps-1">Belum Absen</span>
                            `)
                            $(refPresensiTepatWaktu).html(`
                                <span class="text-success small pt-1 fw-bold">${data?.presensi?.tepat??0}</span> 
                                <span class="text-muted small pt-2 ps-1">Tepat Waktu</span>
                            `)
                            $(refPresensiTelat).html(`
                                <span class="text-danger small pt-1 fw-bold">${data?.presensi?.telat??0}</span> 
                                <span class="text-muted small pt-2 ps-1">Telat</span>
                            `)
                            $(refPresensiR8).html(`
                                <span class="text-success small pt-1 fw-bold">${data?.presensi?.r8??0}</span> 
                                <span class="text-muted small pt-2 ps-1">&ge;8 Jam</span>
                            `)
                            $(refPresensiL8).html(`
                                <span class="text-danger small pt-1 fw-bold">${data?.presensi?.l8??0}</span> 
                                <span class="text-muted small pt-2 ps-1">&lt;8 Jam</span>
                            `)

                            $(refCutiTotal).html(data?.cuti?.total??0)
                            $(refCutiTolak).html(`
                                <span class="text-danger small pt-1 fw-bold">${data?.cuti?.tolak??0}</span> 
                                <span class="text-muted small pt-2 ps-1">Tolak</span>
                            `)
                            $(refCutiTunggu).html(`
                                <span class="text-warning small pt-1 fw-bold">${data?.cuti?.tunggu??0}</span> 
                                <span class="text-muted small pt-2 ps-1">Menunggu</span>
                            `)
                            
                            $(refIzinTotal).html(data?.izin?.total??0)
                            $(refIzinTolak).html(`
                                <span class="text-danger small pt-1 fw-bold">${data?.izin?.tolak??0}</span> 
                                <span class="text-muted small pt-2 ps-1">Tolak</span>
                            `)
                            $(refIzinTunggu).html(`
                                <span class="text-warning small pt-1 fw-bold">${data?.izin?.tunggu??0}</span> 
                                <span class="text-muted small pt-2 ps-1">Menunggu</span>
                            `)
                            
                            $(refSPPDTotal).html(data?.sppd?.total??0)
                            $(refSPPDTolak).html(`
                                <span class="text-danger small pt-1 fw-bold">${data?.sppd?.tolak??0}</span> 
                                <span class="text-muted small pt-2 ps-1">Tolak</span>
                            `)
                            $(refSPPDTunggu).html(`
                                <span class="text-warning small pt-1 fw-bold">${data?.sppd?.tunggu??0}</span> 
                                <span class="text-muted small pt-2 ps-1">Menunggu</span>
                            `)
                        },1000)
                    },
                    error: function(xhr, status, error) {
                        handleAjaxError(xhr, status, error)
                    }
                });
            }
            loadInfo()

            function changeClass(elemen, old_style, new_style) {
                if (elemen.hasClass(old_style)) {
                    elemen.removeClass(old_style);
                }
                if (!elemen.hasClass(new_style)) {
                    elemen.addClass(new_style);
                } 
            }
            const showLayoutAbsen = (state) => {
                $(refAbsenLibur).hide()

                console.log(getCurrentTime().day())
                if(getCurrentTime().day()==0){
                    $(refAbsenForm).hide()
                    $(refAbsenDone).hide()
                    $(refAbsenLibur).show()
                    $(refAbsenMessage).html("")

                    $(refAbsenMessage).hide()
                    $(refAbsenKeterangan).hide()
                    changeClass($(refAbsenSubmit), "btn-warning", "btn-success")
                }
                else if(state=="work"){
                    $(refAbsenForm).hide()
                    $(refAbsenKerja).show()
                    $(refAbsenMessage).html("")

                    $(refAbsenMessage).hide()
                    $(refAbsenKeterangan).hide()
                    changeClass($(refAbsenSubmit), "btn-warning", "btn-success")
                }
                else if(state=="initial"){
                    $(refAbsenForm).show()
                    $(refAbsenDone).hide()
                    $(refAbsenMessage).html("Masih belum terlambat, ayo presensi masuk!")

                    $(refAbsenMessage).hide()
                    $(refAbsenKeterangan).hide()
                    changeClass($(refAbsenSubmit), "btn-warning", "btn-success")
                    $(refAbsenSubmit).html("Presensi Masuk")
                } else if(state=="initial but late"){
                    $(refAbsenForm).show()
                    $(refAbsenDone).hide()
                    $(refAbsenMessage).html("Yah terlambat. kasih tahu kenapa bisa telat")

                    $(refAbsenMessage).show()
                    $(refAbsenKeterangan).show()
                    changeClass($(refAbsenSubmit), "btn-success", "btn-warning")
                    $(refAbsenSubmit).html("Presensi Masuk")
                } else if(state=="less 8 hour work" || state=="8 hour work"){
                    $(refAbsenForm).show()
                    $(refAbsenDone).hide()
                    $(refAbsenMessage).html(state=="less 8 hour work"? "Pulang cepat nih! kasih tahu alasan pulang cepatnya":"-")
                    $(refAbsenSubmit).html("Presensi Keluar")

                    if(state=="8 hour work"){
                        $(refAbsenMessage).hide()
                        $(refAbsenKeterangan).hide()
                        changeClass($(refAbsenSubmit), "btn-warning", "btn-success")
                    } else{
                        changeClass($(refAbsenSubmit), "btn-success", "btn-warning")
                    }
                } else{
                    $(refAbsenForm).hide()
                    $(refAbsenDone).show()
                    $(refAbsenMessage).html("")

                    $(refAbsenMessage).hide()
                    $(refAbsenKeterangan).hide()
                    changeClass($(refAbsenSubmit), "btn-warning", "btn-success")
                }
            }
            
            function rangeMingguan(tanggal){
                const date = new Date(tanggal);
                const dayOfWeek = date.getDay();
                const daysToMonday = (dayOfWeek + 6) % 7; // Number of days to the previous Monday
                const daysToSunday = 7 - dayOfWeek; // Number of days to the next Sunday
                const mondayDate = new Date(date);

                mondayDate.setDate(date.getDate() - daysToMonday);
                const sundayDate = new Date(date);
                sundayDate.setDate(date.getDate() + daysToSunday);
                const formatDate = (d) => d.toISOString().split('T')[0];
                return {
                    senin: formatDate(mondayDate),
                    minggu: formatDate(sundayDate),
                };
            }
            function rangeBulanan(tanggal){
                const date = new Date(tanggal);
                const tanggal_awal = new Date(date);
                tanggal_awal.setDate(1); // Set the date to the first day of the month
                const tanggal_akhir = new Date(date);
                // Increment the month by 1, then set the date to 0 to get the last day of the month
                tanggal_akhir.setMonth(tanggal_akhir.getMonth() + 1);
                tanggal_akhir.setDate(0);
                const formatDate = (d) => d.toISOString().split('T')[0];

                return {
                    tanggal_awal: formatDate(tanggal_awal),
                    tanggal_akhir: formatDate(tanggal_akhir),
                };
            }
            
            const isBefore8Time = () => {
                const currentTime = getCurrentTime();
                const checkBefore8AM = currentTime.isBefore(currentTime.clone().startOf('day').add(timeAbsen, 'hours').add('1', 'minutes'));
                return checkBefore8AM;
            }
            const has8hour = () => { //kena
                if(absenMasuk.isEmpty()) return false;

                let lama = timeAbsen-1
                if(getCurrentTime().day()==5){ //jumat
                    lama = timeAbsen-1
                } else if(getCurrentTime().day()==6){ //sabtu
                    lama = timeAbsen-3
                }
                const masuk = moment(absenMasuk).tz('Asia/Jakarta')
                // console.info(getCurrentTime().format('YYYY-MM-DD HH:mm:ss'), masuk.format('YYYY-MM-DD HH:mm:ss'), masuk.clone().add(timeAbsen, 'hours').format('YYYY-MM-DD HH:mm:ss'))

                const check = getCurrentTime().isAfter(masuk.add(lama, 'hours'));
                return check;
            }
            const checkCurrentAbove15 = () => {
                if(absenMasuk.isEmpty()) return false;
                
                let jam_pulang = '14:59:00'
                if(getCurrentTime().day()==5){
                    jam_pulang = '13:59:00'
                } else if(getCurrentTime().day()==6){
                    jam_pulang = '11:59:00'
                }
                const requireCheckout = !isLate()? moment(dateNow+' '+jam_pulang).tz('Asia/Jakarta'):moment(absenMasuk).tz('Asia/Jakarta').startOf('day').add(timeAbsen, 'hours');
                const check = getCurrentTime().isSameOrAfter(requireCheckout);
                return check;
            }
            const isLate = () => {
                if(absenMasuk.isEmpty()) return false;

                const currentTime = (absenMasuk == null ? getCurrentTime() : moment(absenMasuk)).tz('Asia/Jakarta');
                const absenMasukTime = moment(dateNow + ' '+ timeAbsenString).tz('Asia/Jakarta').add('1', 'minutes');
                const checkLate = currentTime.isAfter(absenMasukTime);
                return checkLate;
            }
            const customCase = () => {
                if(absenMasuk.isEmpty()) return false;

                const currentTime = (absenMasuk == null ? getCurrentTime() : moment(absenMasuk)).tz('Asia/Jakarta').add('30', 'minutes');
                const custom = moment().isAfter(currentTime);
                return custom;
            }

            $(refAbsenSubmit).click(async function(e){
                e.preventDefault();

                const exec = getCurrentTime().format('YYYY-MM-DD HH:mm:ss')
                const keterangan = $(refAbsenKeterangan).val();
                let type = null

                let data = new FormData();
                if(absenMasuk.isEmpty()){
                    data.append("type", 'absen_masuk')
                    data.append("nidn", '{{Session::get("nidn")}}')
                    data.append("nip", '{{Session::get("nip")}}')
                    data.append('absen_masuk', exec)
                    data.append("catatan_telat",keterangan)
                    type = "masuk";
                } else if(!absenMasuk.isEmpty() && absenKeluar.isEmpty()){
                    data.append("type", 'absen_keluar')
                    data.append("nidn", '{{Session::get("nidn")}}')
                    data.append("nip", '{{Session::get("nip")}}')
                    data.append('absen_keluar', exec)
                    data.append("catatan_pulang",keterangan)
                    type = "keluar";
                }
                data.append("tanggal",moment().format('YYYY-MM-DD'))

                let response = await fetch('https://api.ipify.org?format=json');
                response = await response.json();
                const ipAddress = response.ip

                if(!(ipAddress.match(/103\.169/)!==null || ipAddress.match(/2001:df0:3140/)!=null)){
                    alert(`berada diluar jaringan universitas pakuan`);
                } else{
                    $.ajax({
                        url: "{{ route('api.presensi.index') }}",
                        method: 'POST',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            console.log(response)

                            if(response.status=="ok"){
                                if(type=="masuk"){
                                    absenMasuk = exec
                                    catatanTelat = keterangan
                                } else if(type=="keluar"){
                                    absenKeluar = exec
                                    catatanPulang = keterangan
                                }
                                $(refAbsenKeterangan).val('')
                                loadInfo()
                                calendar.refetchEvents()
                            }
                            alert(response.message);
                        },
                        error: function(xhr, status, error) {
                            handleAjaxError(xhr, status, error)
                        }
                    });
                }
            })
            setInterval(function(){
                console.log({
                    "custom":customCase(),
                    "absenMasuk":absenMasuk,
                    "absenKeluar":absenKeluar,
                    "isBefore8Time":isBefore8Time(),
                    "isLate":isLate(),
                    "has8hour":has8hour(),
                    "checkCurrentAbove15":checkCurrentAbove15()
                })
                if(!absenMasuk.isEmpty() && !absenKeluar.isEmpty()){
                    showLayoutAbsen("done")
                } else if(absenMasuk.isEmpty()){ //belum absen jam <08:00
                    showLayoutAbsen(isBefore8Time()? "initial":"initial but late")
                } else if(customCase()){
                    if( (isLate() && !has8hour()) || (!isLate() && !checkCurrentAbove15()) ){
                        showLayoutAbsen("less 8 hour work") 
                    } else {
                        showLayoutAbsen("8 hour work")   
                    }
                } else {
                    showLayoutAbsen("work")
                }

                const _jamMasuk = moment(absenMasuk).tz('Asia/Jakarta')
                const _JamAturanmasuk = moment(dateNow+' 08:00:00').tz('Asia/Jakarta');
                const selisihJam = _jamMasuk.diff(_JamAturanmasuk, 'hours');
                $(refInfoAbsenMasuk).html(absenMasuk.isEmpty()? getCurrentTime().format("HH:mm:ss"):moment(absenMasuk).tz('Asia/Jakarta').format("HH:mm:ss"))
                $(refInfoAbsenTelat).html(absenMasuk.isEmpty()? "-":`${selisihJam<0? '0':selisihJam} Jam`)

                $(refInfoAbsenCatatanTelat).html(catatanTelat.isEmpty()? "-":catatanTelat)
                $(refInfoAbsenCatatanPulang).html(catatanPulang.isEmpty()? "-":catatanPulang)

                const waktuKeluar = (absenKeluar.isEmpty? getCurrentTime():moment(absenKeluar)).tz('Asia/Jakarta');
                const selisihKeluarMenit = waktuKeluar.diff(moment(absenMasuk).tz('Asia/Jakarta'), 'minutes')
                const selisihKeluarJam = waktuKeluar.diff(moment(absenMasuk).tz('Asia/Jakarta'), 'hours')

                $(refInfoAbsenKeluar).html(absenKeluar.isEmpty()? (absenMasuk.isEmpty()? "-":getCurrentTime().format("HH:mm:ss")):moment(absenKeluar).tz('Asia/Jakarta').format("HH:mm:ss"))
                $(refInfoAbsenJamKerja).html(
                    (
                        absenMasuk.isEmpty()
                    )? "-":`${selisihKeluarJam>0? selisihKeluarJam:selisihKeluarMenit} ${selisihKeluarJam>0? 'Jam':'menit'}`
                )
            }, 1000);

            const columns = [
                    {
                        data: 'DT_RowIndex', 
                        name: 'DT_RowIndex', 
                        sWidth:'3%'
                    },
                    {
                        data: 'nama', 
                        name: 'nama',
                        render: function ( data, type, row, meta ) {
                            return data;
                        }
                    },
                    {
                        data: 'tujuan', 
                        name: 'tujuan',
                        render: function ( data, type, row, meta ) {
                            return data;
                        }
                    },
                    {
                        data: 'catatan', 
                        name: 'catatan',
                        render: function ( data, type, row, meta ) {
                            return data;
                        }
                    },
            ];
            $('#tbCutiDosen').DataTable( {
                ajax: `{{route('datatable.ActiveCuti.index')}}?nidn=${nidn}`,
                columns: columns
            });
            $('#tbCutiPegawai').DataTable( {
                ajax: `{{route('datatable.ActiveCuti.index')}}?nip=${nip}`,
                columns: columns
            });
        });
    </script>
@endpush