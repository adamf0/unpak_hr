@extends('template.index')
 
@section('page-title')
    <x-page-title title="SPPD">
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">SPPD</li>
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
                    <a href="{{ route('sppd.create') }}" class="btn btn-primary">Tambah</a>
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
                                <x-input-select title="Jenis SPPD" name="jenis_sppd" class="jenis_sppd"></x-input-select>
                            </div>
                            <div class="col-3">
                                <x-input-select title="Status" name="status" class="status"></x-input-select>
                            </div>
                            <div class="col-5">
                                <x-input-text title="Tanggal Mulai" name="tanggal_berangkat" class="tanggal_berangkat" default=""/>
                            </div>
                            <div class="col-5">
                                <x-input-text title="tanggal Akhir" name="tanggal_kembali" class="tanggal_kembali" default=""/>
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
                                        @if (!in_array(Session::get('levelActive'),["pegawai","dosen"]))
                                        <th>Nama</th>
                                        @endif
                                        <th>Jenis SPPD</th>
                                        <th>Tanggal Berangakat</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Tujuan</th>
                                        <th>Keterangan</th>
                                        <th>Anggota</th>
                                        <th>Status</th>
                                        <th>Catatan</th>
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
            const column = level=="pegawai" || level=="dosen"? 
            [
                {
                    data: 'DT_RowIndex', 
                    name: 'DT_RowIndex', 
                    sWidth:'3%'
                },
                {
                    data: 'jenis_sppd', 
                    name: 'jenis_sppd',
                    render: function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    data: 'tanggal_berangkat', 
                    name: 'tanggal_berangkat',
                    render: function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    data: 'tanggal_kembali', 
                    name: 'tanggal_kembali',
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
                    data: 'keterangan', 
                    name: 'keterangan',
                    render: function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    data: 'anggota', 
                    name: 'anggota',
                    render: function ( data, type, row, meta ) {
                        let list_anggota = '<ol>';
                        data.forEach((item, index, arr)=>{
                            list_anggota += `<li>${item.nama??"NA"}</li>`;
                        })
                        list_anggota += '</ol>';
                        return list_anggota;
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
                    data: 'catatan', 
                    name: 'catatan',
                    render: function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    data: 'action', 
                    name: 'action'
                },
            ]:
            [
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
                    data: 'jenis_sppd', 
                    name: 'jenis_sppd',
                    render: function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    data: 'tanggal_berangkat', 
                    name: 'tanggal_berangkat',
                    render: function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    data: 'tanggal_kembali', 
                    name: 'tanggal_kembali',
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
                    data: 'keterangan', 
                    name: 'keterangan',
                    render: function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    data: 'anggota', 
                    name: 'anggota',
                    render: function ( data, type, row, meta ) {
                        let list_anggota = '<ol>';
                        data.foreach(d=>{
                            list_anggota += `<li>${data.nama??"NA"} - ${data.nidn??data.nip??"NA"}</li>`;
                        })
                        list_anggota += '</ol>';
                        return list_anggota;
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
                    data: 'catatan', 
                    name: 'catatan',
                    render: function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    data: 'action', 
                    name: 'action'
                },
            ];

            let table = eTable({
                url: `{{ route('datatable.SPPD.index') }}?level=${level}&nidn=${nidn}&nip=${nip}`,
            }, column);

            let modal = new bootstrap.Modal(document.getElementById('modal'));
            let modalTitle = $('.modalTitle');
            let modalBody = $('.modalBody');

            $('#tb tbody').on('click', '.btn-reject', function(e) {
                e.preventDefault();
                const rowData = table.row($(this).closest('tr')).data();
                modalTitle.text("Informasi Penolakan");
                modalBody.html(`
                <div class="row">
                    <input type="hidden" class="id_sppd" value="${rowData?.id}">
                    <div class="col-12">
                        <x-text title="Catatan" name="catatan" class="catatan" default="${rowData?.catatan}"/>
                    </div>
                    <div class="col-12">
                        <input type="submit" class="btn btn-success btn-tolak" value="Simpan">
                    </div>
                </div>`);
                modal.show();
            });
            $('#tb tbody').on('click', '.btn-download-pdf', function(e) {
                e.preventDefault();
                const rowData = table.row($(this).closest('tr')).data();
                
                const data = {
                    _token: '{{ csrf_token() }}',
                    id : rowData?.id,
                    type_export : "pdf"
                };

                console.log(data)
                $.redirect(`{{url('sppd/export')}}`,data,"GET","_blank")
            });

            $('#modal').on('click', '.btn-tolak', function(e){
                const id = $(".id_sppd").val();
                const catatan = $(".catatan").val();
                let dataForm = new FormData()
                dataForm.append("id",id)
                dataForm.append("catatan",catatan)
                dataForm.append("pic","{{Session::get('id')}}")

                $.ajax({
                    url: "{{ route('api.sppd.reject') }}",
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
            let cetak_jenis_sppd = null;
            let cetak_status = null;
            let cetak_tanggal_berangkat = null;
            let cetak_tanggal_kembali = null;
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
            load_dropdown('.jenis_sppd', null, `{{ route('select2.JenisSPPD.List') }}`, null, '-- Pilih Jenis sppd --');
            load_dropdown('.status', status, null, null, '-- Pilih Status --');
            load_dropdown('.type_export', type_export, null, null, '-- Pilih --');

            $('.tanggal_berangkat').datepicker({
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
            $('.tanggal_kembali').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlidht: true,
                orientation: 'bottom',
                datesDisabled:[],
                daysOfWeekDisabled:[],
                startDate:"{{old('tanggal_berangkat')}}",
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

            $('.tanggal_berangkat').change(function(e) {
                const min = $(this).val()
                cetak_tanggal_berangkat = min
                $('.tanggal_kembali').datepicker('setStartDate', min);
            });
            $('.tanggal_kembali').change(function(e) {
                const min = $(this).val()
                cetak_tanggal_kembali = min
            });
            $('.jenis_sppd').on('select2:select', function(e) {
                // var data = e.params.data;
                cetak_jenis_sppd = $(this).val()
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
                    jenis_sppd : cetak_jenis_sppd,
                    status : cetak_status,
                    tanggal_berangkat : cetak_tanggal_berangkat,
                    tanggal_kembali : cetak_tanggal_kembali,
                    type_export : cetak_type_export
                };

                console.log(data)
                $.redirect(`{{url('sppd/export')}}`,data,"GET","_blank")
            });
        });
    </script>
@endpush