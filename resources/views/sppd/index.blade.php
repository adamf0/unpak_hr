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
                <a href="{{ route('sppd.create') }}" class="btn btn-primary">Tambah</a>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tb" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NIDN</th>
                                        <th>NIP</th>
                                        <th>Jenis SPPD</th>
                                        <th>Tanggal Berangakt</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Tujuan</th>
                                        <th>Keterangan</th>
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
                url: `{{ route('datatable.SPPD.index') }}?level=${level}&nidn=${nidn}&nip=${nip}`,
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
        });
    </script>
@endpush