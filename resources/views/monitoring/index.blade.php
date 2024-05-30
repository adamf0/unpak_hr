@extends('template.index')
 
@section('page-title')
    <x-page-title title="Monitoring">
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
    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h4 class="card-title">Info Presensi {{Carbon::now()->setTimezone('Asia/Jakarta')->format("d F Y")}}</h4>
                    </div>
                    <div class="col-12">
                        <h4 class="card-title">Dosen</h4>
                        <table class="table table-stripped tb_absen_dosen">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <td>Nama</td>
                                    <td>Tanggal</td>
                                    <td>Absen Masuk</td>
                                    <td>Catatan Telat</td>
                                    <td>Absen Keluar</td>
                                    <td>Catatan Pulang</td>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="col-12">
                        <h4 class="card-title">Pegawai</h4>
                        <table class="table table-stripped tb_absen_pegawai">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <td>Nama</td>
                                    <td>Tanggal</td>
                                    <td>Absen Masuk</td>
                                    <td>Catatan Telat</td>
                                    <td>Absen Keluar</td>
                                    <td>Catatan Pulang</td>
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
            const nidn = `{{Session::get('nidn')}}`
            const nip = `{{Session::get('nip')}}`
            const level = `{{Session::get('levelActive')}}`

            const column = [
                {
                    data: 'DT_RowIndex', 
                    name: 'DT_RowIndex', 
                    sWidth:'3%'
                },
                {
                    data: 'nama', 
                    name: 'nama',
                    render: function ( data, type, row, meta ) {
                        return data??"NA";
                    }
                },
                {
                    data: 'tanggal', 
                    name: 'tanggal',
                    render: function ( data, type, row, meta ) {
                        return data??"NA";
                    }
                },
                {
                    data: 'masuk', 
                    name: 'masuk',
                    render: function ( data, type, row, meta ) {
                        return data??"-";
                    }
                },
                {
                    data: 'catatan_telat', 
                    name: 'catatan_telat',
                    render: function ( data, type, row, meta ) {
                        return data??"-";
                    }
                },
                {
                    data: 'keluar', 
                    name: 'keluar',
                    render: function ( data, type, row, meta ) {
                        return data??"-";
                    }
                },
                {
                    data: 'catatan_pulang', 
                    name: 'catatan_pulang',
                    render: function ( data, type, row, meta ) {
                        return data??"-";
                    }
                },
            ];

            let table_absen_dosen = eTable({
                url: `{{ route('datatable.Presensi.index') }}?filter=dosen&nidn=${nidn}&nip=${nip}`,
            }, column,
            function( row, data ){},
            function( settings ){},
            ".tb_absen_dosen");

            let table_absen_pegawai = eTable({
                url: `{{ route('datatable.Presensi.index') }}?filter=pegawai&nidn=${nidn}&nip=${nip}`,
            }, column,
            function( row, data ){},
            function( settings ){},
            ".tb_absen_pegawai");

            const getCurrentTime = () => {
                return moment().tz('Asia/Jakarta')
            }
            String.prototype.isEmpty = function() {
                return (this.length === 0 || !this.trim());
            };
        });
    </script>
@endpush