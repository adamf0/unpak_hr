@extends('template.index')
 
@section('page-title')
    <x-page-title title="Jenis SPPD">
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Jenis SPPD</li>
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
                <a href="{{ route('jenis_sppd.create') }}" class="btn btn-primary">Tambah</a>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tb" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
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
            
            let table = eTable({
                url: `{{ route('datatable.JenisSPPD.index') }}`,
            }, [
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
                    data: 'action', 
                    name: 'action'
                },
            ]);
        });
    </script>
@endpush