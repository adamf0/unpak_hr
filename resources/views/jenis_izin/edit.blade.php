@extends('template.index')
 
@section('page-title')
    <x-page-title title="Jenis Izin">
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('jenis_izin.index') }}">Jenis Izin</a></li>
            <li class="breadcrumb-item active">Ubah</li>
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
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('jenis_izin.update') }}" method="post">
                                @csrf
                                <div class="row">
                                    <input type="hidden" name="id" class="@error('id') is-invalid @enderror" value="{{ old('id',$JenisIzin->GetId()) }}">
                                    <div class="mb-3">
                                        <x-input-text title="Nama" name="nama" default="{{ old('nama',$JenisIzin->GetNama()) }}"/>
                                    </div>
                                </div>
                                
                                <input type="submit" name="submit" class="btn btn-primary" value="submit">
                            </form>
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
        
        });
    </script>
@endpush