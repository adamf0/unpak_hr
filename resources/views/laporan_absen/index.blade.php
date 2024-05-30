@extends('template.index')
 
@section('page-title')
    <x-page-title title="Laporan Absen">
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Laporan Absen</li>
            </ol>
        </nav>
    </x-page-title>
@stop

@section('content')
<style>
    .column_min{
        min-width: 200px !important;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-12">
                {{ Utility::showNotif() }}
            </div>
            <div class="col-12">
            <div class="card">
                <div class="card-body row">
                    <div class="col-6">
                        <x-input-text title="NIDN" name="nidn" class="nidn" default=""/>
                    </div>
                    <div class="col-6">
                        <x-input-text title="NIP" name="nip" class="nip" default=""/>
                    </div>
                    <div class="col-5">
                        <x-input-text title="Tanggal Mulai" name="tanggal_mulai" class="tanggal_mulai" default=""/>
                    </div>
                    <div class="col-5">
                        <x-input-text title="Tanggal Akhir" name="tanggal_akhir" class="tanggal_akhir" default=""/>
                    </div>
                    <div class="col-2">
                        <x-input-select title="Cetak Sebagai" name="type_export" class="type_export"></x-input-select>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary btn_cetak">Cetak</button>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body row">
                        <div class="col-12">
                            <h5>Per Tanggal {{ $start }} - {{ $end }}</h5>
                        </div>
                        <div class="col-12 table-responsive">
                            <table id="tb" class="table table-striped text-center">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        @foreach ($list_tanggal as $tanggal)
                                        <th>{{Carbon::parse($tanggal)->setTimezone('Asia/Jakarta')->format('d F')}}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('scripts')
    <script type="text/javascript" src="{{ Utility::loadAsset('my.js') }}"></script>
    <script>
        $(document).ready(function () {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            
            const nidn = `{{Session::get('nidn')}}`
            const nip = `{{Session::get('nip')}}`
            const level = `{{Session::get('levelActive')}}`
            
            $('#tb').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("datatable.LaporanAbsen.index") }}',
                columns: [
                    { 
                        data: 'pengguna', 
                        name: 'pengguna',
                        render: function ( data, type, row, meta ) {
                            console.log(data)
                            return data?.nama_dosen??data?.nama??"NA"
                        }
                    },
                    @foreach ($list_tanggal as $tanggal)
                    { 
                        data: `{{ $tanggal }}`, 
                        name: `{{ $tanggal }}`,
                        render: function ( data, type, row, meta ) {
                            var aturan_jam = "08:00 - 15:00";
                            var tgl = moment('{{$tanggal}}');
                            if (tgl.day() === 5) { // 5 is Friday in moment.js
                                aturan_jam = "08:00 - 14:00";
                            } else if (tgl.day() === 6) { // 6 is Saturday in moment.js
                                aturan_jam = "08:00 - 12:00";
                            }

                            var keterangan = "";
                            data.forEach(function(d) {
                                if (d.info?.type === "absen") {
                                    if (!d.info?.keterangan?.masuk && !d.info?.keterangan?.keluar) {
                                        keterangan = "<span class='badge bg-danger'>Tidak Masuk</span>";
                                    } else if (d.info?.keterangan?.masuk && !d.info?.keterangan?.keluar) {
                                        var masuk = moment(d.info?.keterangan?.masuk);
                                        keterangan = "<span class='badge bg-success'>" + masuk.format('HH:mm') + "</span> - <span class='badge bg-danger'>Masih Masuk</span>";
                                    } else {
                                        var masuk = moment(d.info?.keterangan?.masuk);
                                        var keluar = moment(d.info?.keterangan?.keluar);
                                        keterangan = "<span class='badge bg-success'>" + masuk.format('HH:mm') + "</span> - <span class='badge bg-danger'>" + keluar.format('HH:mm') + "</span>";
                                    }
                                } else if (d.info.type === "izin") {
                                    keterangan = "<span class='badge bg-primary'>Izin</span>";
                                } else if (d.info.type === "cuti") {
                                    keterangan = "<span class='badge bg-warning text-black'>Cuti</span>";
                                }
                            })
 
                            return '<table>' +
                                '<tr>' +
                                    '<td class="column_min">' + aturan_jam + '</td>' +
                                '</tr>' +
                                '<tr>' +
                                    '<td>' + keterangan + '</td>' +
                                '</tr>' +
                            '</table>';
                        } 
                    },
                    @endforeach
                ]
            });
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            let cetak_nidn = null;
            let cetak_nip = null;
            let cetak_tanggal_mulai = null;
            let cetak_tanggal_akhir = null;
            let cetak_type_export = null;

            const status = [
                {
                    "id":"semua",
                    "text":"Semua",
                },
                {
                    "id":"menunggu",
                    "text":"Menunggu",
                },
                {
                    "id":"tolak",
                    "text":"Tolak",
                },
                {
                    "id":"terima",
                    "text":"Terima",
                },
            ];
            const type_export = [
                {
                    "id":"pdf",
                    "text":"PDF",
                },
                {
                    "id":"xls",
                    "text":"Excel",
                },
            ];
            load_dropdown('.type_export', type_export, null, null, '-- Pilih --');

            $('.tanggal_mulai').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlidht: true,
                orientation: 'bottom',
                datesDisabled:[],
                daysOfWeekDisabled:[],
                }).on('show', function(e) {
                // Mengatur posisi popover Datepicker ke center (middle).
                var $input = $(e.currentTarget);
                var $datepicker = $input.data('datepicker').picker;
                var $parent = $input.parent();
                var bottom = ($parent.offset().bottom - $datepicker.outerHeight()) + $parent.outerHeight();
                $datepicker.css({
                    bottom: bottom,
                    left: $parent.offset().left
                });
            });
            $('.tanggal_akhir').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlidht: true,
                orientation: 'bottom',
                datesDisabled:[],
                daysOfWeekDisabled:[],
                startDate:"{{old('tanggal_mulai')}}",
                }).on('show', function(e) {
                // Mengatur posisi popover Datepicker ke center (middle).
                var $input = $(e.currentTarget);
                var $datepicker = $input.data('datepicker').picker;
                var $parent = $input.parent();
                var bottom = ($parent.offset().bottom - $datepicker.outerHeight()) + $parent.outerHeight();
                $datepicker.css({
                    bottom: bottom,
                    left: $parent.offset().left
                });
            });

            $('.tanggal_mulai').change(function(e) {
                const min = $(this).val()
                cetak_tanggal_mulai = min
                $('.tanggal_akhir').datepicker('setStartDate', min);
            });
            $('.tanggal_akhir').change(function(e) {
                const min = $(this).val()
                cetak_tanggal_akhir = min
            });
            $('.type_export').on('select2:select', function(e) {
                // var data = e.params.data;
                cetak_type_export = $(this).val()
            });
            $('.nidn').on('change', function(e) {
                cetak_nidn = $(this).val()
            });
            $('.nip').on('change', function(e) {
                cetak_nip = $(this).val()
            });
            $('.btn_cetak').click(function(e){
                e.preventDefault();

                const data = {
                    _token: '{{ csrf_token() }}',
                    nidn : cetak_nidn,
                    nip : cetak_nip,
                    tanggal_mulai : cetak_tanggal_mulai,
                    tanggal_akhir : cetak_tanggal_akhir,
                    type_export : cetak_type_export
                };

                console.log(data)
                $.redirect(`{{url('laporan_absen/export')}}`,data,"GET","_blank")
            });
        });
    </script>
@endpush