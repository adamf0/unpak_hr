@extends('template.index')
 
@section('page-title')
    <x-page-title title="Izin">
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Izin</li>
            </ol>
        </nav>
    </x-page-title>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-12">
                {{ Utility::showNotif() }}
            </div>
            <div class="col-12">
                @if (Utility::hasUser())
                    <a href="{{ route('izin.create') }}" class="btn btn-primary">Tambah</a>
                @else
                    <div class="card">
                        <div class="card-body row">
                            <div class="col-3">
                                <x-input-text title="NIDN" name="nidn" class="nidn" default=""/>
                            </div>
                            <div class="col-3">
                                <x-input-text title="NIP" name="nip" class="nip" default=""/>
                            </div>
                            <div class="col-3">
                                <x-input-select title="Jenis Izin" name="jenis_izin" class="jenis_izin"></x-input-select>
                            </div>
                            <div class="col-3">
                                <x-input-select title="Status" name="status" class="status"></x-input-select>
                            </div>
                            <div class="col-5">
                                <x-input-text title="Tanggal Mulai" name="tanggal_mulai" class="tanggal_mulai" default=""/>
                            </div>
                            <div class="col-5">
                                <x-input-text title="tanggal Akhir" name="tanggal_akhir" class="tanggal_akhir" default=""/>
                            </div>
                            <div class="col-2">
                                <x-input-select title="Cetak Sebagai" name="type_export" class="type_export"></x-input-select>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary btn_cetak">Cetak</button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tb" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NIDN</th>
                                        <th>NIP</th>
                                        <th>Jenis Izin</th>
                                        <th>Tanggal Izin</th>
                                        <th>Tujuan</th>
                                        <th>Dokumen</th>
                                        <th>Catatan</th>
                                        <th>Status</th>
                                        <th>Action</th>
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
<div class="modal modal-lg fade" id="modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title modalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modalBody"></div>
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
            let table = eTable({
                url: `{{ route('datatable.Izin.index') }}?level=${level}&nidn=${nidn}&nip=${nip}`,
            }, [
                {
                    data: 'DT_RowIndex', 
                    name: 'DT_RowIndex', 
                    sWidth:'3%'
                },
                {
                    data: 'nidn', 
                    name: 'nidn',
                    render: function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    data: 'nip', 
                    name: 'nip',
                    render: function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    data: 'jenis_izin', 
                    name: 'jenis_izin',
                    render: function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    data: 'tanggal_pengajuan', 
                    name: 'tanggal_pengajuan',
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
                    data: 'dokumen', 
                    name: 'dokumen',
                    render: function ( data, type, row, meta ) {
                        return (data?.url? `<a href="${data?.url}" class="btn btn-success" target="_blank">Buka File</a>`:"");
                    }
                },
                {
                    data: 'catatan', 
                    name: 'catatan',
                    render: function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    data: 'status', 
                    name: 'status',
                    render: function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    data: 'action', 
                    name: 'action'
                },
            ]);

            let modal = new bootstrap.Modal(document.getElementById('modal'));
            let modalTitle = $('.modalTitle');
            let modalBody = $('.modalBody');

            $('#tb tbody').on('click', '.btn-reject', function(e) {
                e.preventDefault();
                const rowData = table.row($(this).closest('tr')).data();
                modalTitle.text("Informasi Penolakan");
                modalBody.html(`
                <div class="row">
                    <input type="hidden" class="id_izin" value="${rowData?.id}">
                    <div class="col-12">
                        <x-text title="Catatan" name="catatan" class="catatan" default="${rowData?.catatan}"/>
                    </div>
                    <div class="col-12">
                        <input type="submit" class="btn btn-success btn-tolak" value="Simpan">
                    </div>
                </div>`);
                modal.show();
            });

            $('#modal').on('click', '.btn-tolak', function(e){
                const id = $(".id_izin").val();
                const catatan = $(".catatan").val();
                let dataForm = new FormData()
                dataForm.append("id",id)
                dataForm.append("catatan",catatan)
                dataForm.append("pic","{{Session::get('id')}}")

                $.ajax({
                    url: "{{ route('api.izin.reject') }}",
                    method: 'POST',
                    data: dataForm,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response)
                        modal.hide();
                        alert(response.message);
                        table.ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        handleAjaxError(xhr, status, error)
                        modal.hide();
                        table.ajax.reload();
                    }
                });
            });

            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            let cetak_nidn = null;
            let cetak_nip = null;
            let cetak_jenis_izin = null;
            let cetak_status = null;
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
            load_dropdown('.jenis_izin', null, `{{ route('select2.JenisIzin.List') }}`, null, '-- Pilih Jenis izin --');
            load_dropdown('.status', status, null, null, '-- Pilih Status --');
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
            $('.jenis_izin').on('select2:select', function(e) {
                // var data = e.params.data;
                cetak_jenis_izin = $(this).val()
            });
            $('.status').on('select2:select', function(e) {
                // var data = e.params.data;
                cetak_status = $(this).val()
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
                    jenis_izin : cetak_jenis_izin,
                    status : cetak_status,
                    tanggal_mulai : cetak_tanggal_mulai,
                    tanggal_akhir : cetak_tanggal_akhir,
                    type_export : cetak_type_export
                };

                console.log(data)
                $.redirect(`{{url('izin/export')}}`,data,"GET","_blank")
            });
        });
    </script>
@endpush