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
                            <h6 class="mx-3 flex-grow-1"><span class="placeholder col-2"></span></h6>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="grid-card">
                            <div>
                                <span class="text-success small pt-1 fw-bold"><span class="placeholder col-2"></span></span> 
                                <span class="text-muted small pt-2 ps-1">Tepat Waktu</span>                            
                            </div>
                            <div>
                                <span class="text-danger small pt-1 fw-bold"><span class="placeholder col-2"></span></span> 
                                <span class="text-muted small pt-2 ps-1">Telat</span>                            
                            </div>
                            <div>
                                <span class="text-success small pt-1 fw-bold"><span class="placeholder col-2"></span></span> 
                                <span class="text-muted small pt-2 ps-1">&ge;8 Jam</span>                            
                            </div>
                            <div>
                                <span class="text-danger small pt-1 fw-bold"><span class="placeholder col-2"></span></span> 
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
                            <h6 class="mx-3 flex-grow-1"><span class="placeholder col-2"></span></h6>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="grid-card">
                            <div>
                                <span class="text-danger small pt-1 fw-bold"><span class="placeholder col-2"></span></span> 
                                <span class="text-muted small pt-2 ps-1">Tolak</span>                            
                            </div>
                            <div>
                                <span class="text-warning small pt-1 fw-bold"><span class="placeholder col-2"></span></span> 
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
                            <h6 class="mx-3 flex-grow-1"><span class="placeholder col-2"></span></h6>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="grid-card">
                            <div>
                                <span class="text-danger small pt-1 fw-bold"><span class="placeholder col-2"></span></span> 
                                <span class="text-muted small pt-2 ps-1">Tolak</span>                            
                            </div>
                            <div>
                                <span class="text-warning small pt-1 fw-bold"><span class="placeholder col-2"></span></span> 
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
                            <h6 class="mx-3 flex-grow-1"><span class="placeholder col-2"></span></h6>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="grid-card">
                            <div>
                                <span class="text-danger small pt-1 fw-bold"><span class="placeholder col-2"></span></span> 
                                <span class="text-muted small pt-2 ps-1">Tolak</span>                            
                            </div>
                            <div>
                                <span class="text-warnings small pt-1 fw-bold"><span class="placeholder col-2"></span></span> 
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
                                <span class="text-success small pt-1 fw-bold">Masuk</span> 
                                <span class="text-muted small pt-2 ps-1 info_absen_masuk"><span class="placeholder col-2"></span></span>                            
                            </div>
                            <div>
                                <span class="text-success small pt-1 fw-bold">Keluar</span> 
                                <span class="text-muted small pt-2 ps-1 info_absen_keluar"><span class="placeholder col-2"></span></span>                            
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
                    <img src="{{ Utility::loadAsset('assets/img/delivery_man_5_with_dog.png') }}" alt="sudah pulang">
                    <h4>Anda udah Pulang</h4>
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
                            <li class="list-group-item">Jam Masuk & Jam Pulang WAJIB terhubung/Koneksi JARINGAN WIFI/LAN UNIVERSITAS PAKUAN Seperti (UNPAK Access / UNPAK Guest/ Eduroam)</li>
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
            const getCurrentTime = () => {
                return moment("2024-05-17 18:11:00").tz('Asia/Jakarta')
            }

            const refAbsenForm = '.absen_form';
            const refAbsenMessage = '.absen_message';
            const refAbsenSubmit = '.absen_submit';
            const refAbsenKeterangan = '.absen_keterangan'
            const refAbsenDone = '.absen_done';
            const refInfoAbsenMasuk = '.info_absen_masuk'
            const refInfoAbsenKeluar = '.info_absen_keluar'
            const refInfoAbsenTelat = '.info_absen_telat'
            const refInfoAbsenJamKerja = '.info_absen_jam_kerja'
            
            const dateNow = getCurrentTime().format('YYYY-MM-DD');
            const timeAbsenString = "08:00:00"; 
            const timeAbsen = parseInt(timeAbsenString.split(":")[0]);
            let absenMasuk = '2024-05-17 08:10:00';
            let absenKeluar = '2024-05-17 17:10:00';

            function changeClass(elemen, old_style, new_style) {
                if (elemen.hasClass(old_style)) {
                    elemen.removeClass(old_style);
                }
                if (!elemen.hasClass(new_style)) {
                    elemen.addClass(new_style);
                } 
            }
            const showLayoutAbsen = (state) => {
                if(state=="initial"){
                    $(refAbsenForm).show()
                    $(refAbsenDone).hide()
                    $(refAbsenMessage).html("Masih belum terlambat, ayo presensi masuk!")

                    $(refAbsenMessage).show()
                    $(refAbsenKeterangan).show()
                    changeClass($(refAbsenSubmit), "btn-warning", "btn-success")
                } else if(state=="initial but late"){
                    $(refAbsenForm).show()
                    $(refAbsenDone).hide()
                    $(refAbsenMessage).html("Yah terlambat. kasih tahu kenapa bisa telat")

                    $(refAbsenMessage).show()
                    $(refAbsenKeterangan).show()
                    changeClass($(refAbsenSubmit), "btn-success", "btn-warning")
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
                if(absenMasuk==null || absenMasuk=='') return false;

                let lama = timeAbsen
                if(getCurrentTime().day()==5){
                    lama = timeAbsen-1
                } else if(getCurrentTime().day()==6){
                    lama = timeAbsen-3
                }
                const masuk = moment(absenMasuk).tz('Asia/Jakarta')
                // console.info(getCurrentTime().format('YYYY-MM-DD HH:mm:ss'), masuk.format('YYYY-MM-DD HH:mm:ss'), masuk.clone().add(timeAbsen, 'hours').format('YYYY-MM-DD HH:mm:ss'))

                const check = getCurrentTime().isAfter(masuk.add(lama, 'hours'));
                return check;
            }
            const checkCurrentAbove15 = () => {
                if(absenMasuk==null || absenMasuk=='') return false;
                
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
                if(absenMasuk==null || absenMasuk=='') return false;

                const currentTime = (absenMasuk == null ? getCurrentTime() : moment(absenMasuk)).tz('Asia/Jakarta');
                const absenMasukTime = moment(dateNow + ' '+ timeAbsenString).tz('Asia/Jakarta').add('1', 'minutes');
                const checkLate = currentTime.isAfter(absenMasukTime);
                return checkLate;
            }

            setInterval(function(){
                console.log({
                    "absenMasuk":absenMasuk != null,
                    "isBefore8Time":isBefore8Time(),
                    "isLate":isLate(),
                    "has8hour":has8hour(),
                    "checkCurrentAbove15":checkCurrentAbove15()
                })
                if(absenMasuk!=null && absenMasuk!='' && absenKeluar!=null){
                    showLayoutAbsen("done")
                } else if(absenMasuk == null || absenMasuk==''){ //belum absen jam <08:00
                    showLayoutAbsen(isBefore8Time()? "initial":"initial but late")
                } else{
                    if( (isLate() && !has8hour()) || (!isLate() && !checkCurrentAbove15()) ){
                        showLayoutAbsen("less 8 hour work") 
                    } else {
                        showLayoutAbsen("8 hour work")   
                    }
                }
                $(refInfoAbsenMasuk).html(getCurrentTime().format("HH:mm:ss"))
                $(refInfoAbsenTelat).html(absenMasuk==null || absenMasuk==''? "-":`${getCurrentTime().diff(moment(absenMasuk).tz('Asia/Jakarta'), 'hours')} Jam`)

                $(refInfoAbsenKeluar).html(absenKeluar==null || absenKeluar==''? "-":moment(absenKeluar).tz('Asia/Jakarta').format("HH:mm:ss"))
                $(refInfoAbsenJamKerja).html(
                    (
                        (absenMasuk==null || absenMasuk=='') || 
                        (absenKeluar==null || absenKeluar=='')
                    )? "-":`${moment(absenKeluar).tz('Asia/Jakarta').diff(moment(absenMasuk).tz('Asia/Jakarta'), 'hours')} Jam`
                )
            }, 1000);
        });
    </script>
@endpush