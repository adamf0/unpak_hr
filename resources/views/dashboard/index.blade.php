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
    .scrollme {
        overflow-x: auto;
    }
    .grid-card{
        display: grid;
        grid-template-rows: 2;
        grid-template-columns: minmax(min-content, 1fr) minmax(min-content, 1fr);
    }
    @media (max-width: 350px) {
        .grid-card{
            grid-template-rows: 1;
            grid-template-columns: minmax(min-content, 1fr);
        }       
    }
</style>
<div class="row">
    <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
        <div class="card info-card sales-card">
            <div class="card-body">
                <h5 class="card-title">Presensi</h5>
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex flex-row flex-wrap flex-grow-1 align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-file-earmark-x"></i>
                            </div>
                            <h6 class="mx-3 flex-grow-1">0</h6>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="grid-card">
                            <div>
                                <span class="text-success small pt-1 fw-bold">1000</span> 
                                <span class="text-muted small pt-2 ps-1">Tepat Waktu</span>                            
                            </div>
                            <div>
                                <span class="text-danger small pt-1 fw-bold">10000</span> 
                                <span class="text-muted small pt-2 ps-1">Telat</span>                            
                            </div>
                            <div>
                                <span class="text-success small pt-1 fw-bold">0</span> 
                                <span class="text-muted small pt-2 ps-1">&ge;8 Jam</span>                            
                            </div>
                            <div>
                                <span class="text-danger small pt-1 fw-bold">0</span> 
                                <span class="text-muted small pt-2 ps-1">&lt;8 Jam</span>                            
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
                <h5 class="card-title">Cuti</h5>
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex flex-row flex-wrap flex-grow-1 align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-file-earmark-x"></i>
                            </div>
                            <h6 class="mx-3 flex-grow-1">0</h6>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="grid-card">
                            <div>
                                <span class="text-danger small pt-1 fw-bold">1000</span> 
                                <span class="text-muted small pt-2 ps-1">Tolak</span>                            
                            </div>
                            <div>
                                <span class="text-warning small pt-1 fw-bold">10000</span> 
                                <span class="text-muted small pt-2 ps-1">Menunggu</span>                            
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
                <h5 class="card-title">Izin</h5>
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex flex-row flex-wrap flex-grow-1 align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-file-earmark-x"></i>
                            </div>
                            <h6 class="mx-3 flex-grow-1">0</h6>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="grid-card">
                            <div>
                                <span class="text-danger small pt-1 fw-bold">1000</span> 
                                <span class="text-muted small pt-2 ps-1">Tolak</span>                            
                            </div>
                            <div>
                                <span class="text-warning small pt-1 fw-bold">10000</span> 
                                <span class="text-muted small pt-2 ps-1">Menunggu</span>                            
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
                        <div class="d-flex flex-row flex-wrap flex-grow-1 align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-file-earmark-x"></i>
                            </div>
                            <h6 class="mx-3 flex-grow-1">0</h6>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="grid-card">
                            <div>
                                <span class="text-danger small pt-1 fw-bold">1000</span> 
                                <span class="text-muted small pt-2 ps-1">Tolak</span>                            
                            </div>
                            <div>
                                <span class="text-warnings small pt-1 fw-bold">10000</span> 
                                <span class="text-muted small pt-2 ps-1">Menunggu</span>                            
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
                                <span class="text-success small pt-1 fw-bold">Telat</span> 
                                <span class="text-muted small pt-2 ps-1">40 menit</span>                            
                            </div>
                            <div>
                                <span class="text-success small pt-1 fw-bold">Jam kerja</span> 
                                <span class="text-muted small pt-2 ps-1">1 jam</span>                            
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
                <h5 class="card-title">Ketentuan</h5>
                <div class="row">
                    <div class="col-12">
                        <label>Yah terlambat</label>
                        <textarea name="keterangan" class="form-control" style="min-height: 45vmin;" placeholder="masukkan keterangan"></textarea>
                    </div>
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
                            <li class="list-group-item">am Masuk & Jam Pulang WAJIB terhubung/Koneksi JARINGAN WIFI/LAN UNIVERSITAS PAKUAN Seperti (UNPAK Access / UNPAK Guest/ Eduroam)</li>
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
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.0.0/index.global.min.js'></script>
    <script>
        $(document).ready(function () {
            // $('#tb').DataTable();

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                header: {
                left: 'dayGridMonth,timeGridWeek,timeGridDay',
                center: 'title',
                right: 'listDay,listWeek,month,prevYear,prev,next,nextYear' 
                },
                events: [
                    {
                        title: 'All Day Event',
                        start: '2024-05-01'
                    },
                    {
                        title: 'Long Event',
                        start: '2024-05-01',
                        end: '2024-05-02',
                        // backgroundColor: '#000',
                        borderColor: "transparent",
                        className: "bg-danger"
                    },
                ]
            });
            calendar.render();
        });
    </script>
@endpush