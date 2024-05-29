@extends('template.index')
 
@section('page-title')
    <x-page-title title="Klaim Absen">
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Klaim Absen</li>
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
                <a href="{{ route('klaim_absen.create') }}" class="btn btn-primary">Tambah</a>
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
                                        <th>Tanggal Absen</th>
                                        <th>Jam Masuk</th>
                                        <th>Jam Keluar</th>
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
            const column = level == "pegawai" || level=="dosen"?
            [
                {
                    data: 'DT_RowIndex', 
                    name: 'DT_RowIndex', 
                    sWidth:'3%'
                },
                {
                    data: 'tanggal_jam_masuk_keluar', 
                    name: 'tanggal_jam_masuk_keluar',
                    render: function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    data: 'jam_masuk_klaim', 
                    name: 'jam_masuk_klaim',
                    render: function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    data: 'jam_keluar_klaim', 
                    name: 'jam_keluar_klaim',
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
                    data: 'tanggal_jam_masuk_keluar', 
                    name: 'tanggal_jam_masuk_keluar',
                    render: function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    data: 'jam_masuk_klaim', 
                    name: 'jam_masuk_klaim',
                    render: function ( data, type, row, meta ) {
                        return data;
                    }
                },
                {
                    data: 'jam_keluar_klaim', 
                    name: 'jam_keluar_klaim',
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
            ];

            let table = eTable({
                url: `{{ route('datatable.KlaimAbsen.index') }}?level=${level}&nidn=${nidn}&nip=${nip}`,
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
                    <input type="hidden" class="id_klaim_absen" value="${rowData?.id}">
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
                const id = $(".id_klaim_absen").val();
                const catatan = $(".catatan").val();
                let dataForm = new FormData()
                dataForm.append("id",id)
                dataForm.append("catatan",catatan)
                dataForm.append("pic","{{Session::get('id')}}")

                $.ajax({
                    url: "{{ route('api.klaim_absen.reject') }}",
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

        });
    </script>
@endpush