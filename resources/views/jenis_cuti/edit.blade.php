@extends('template.index')
 
@section('page-title')
    <x-page-title title="Jenis Cuti">
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('jenis_cuti.index') }}">Jenis Cuti</a></li>
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
                            <form action="{{ route('jenis_cuti.update') }}" method="post">
                                @csrf
                                <div class="row">
                                    <input type="hidden" name="id" class="@error('id') is-invalid @enderror" value="{{ old('id',$JenisCuti->GetId()) }}">
                                    <div class="mb-3">
                                        <x-input-text title="Nama" name="nama" default="{{ old('nama',$JenisCuti->GetNama()) }}"/>
                                    </div>
                                    <div class="col-6">
                                        <x-input-number title="Min" name="min" default="{{ old('min',$JenisCuti->GetMin()) }}"/>
                                    </div>
                                    <div class="col-6">
                                        <x-input-number title="Max" name="max" default="{{ old('max',$JenisCuti->GetMax()) }}"/>
                                    </div>
                                    <div class="col-12">
                                        <x-text title="Kondisi" name="kondisi" class="kondisi" default="{!! old('kondisi',$JenisCuti->GetKondisi()) !!}"/>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="dokumen" value="1" id="dokumen" @if($JenisCuti->GetDokumen()) checked @endif>
                                            <label class="form-check-label" for="dokumen">
                                                Wajib Upload Dokumen
                                            </label>
                                        </div>
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