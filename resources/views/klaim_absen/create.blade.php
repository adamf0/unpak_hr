@extends('template.index')
 
@section('page-title')
    <x-page-title title="Klaim Presensi">
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('klaim_absen.index') }}">Klaim Presensi</a></li>
            <li class="breadcrumb-item active">Tambah</li>
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
                            <form action="{{ route('klaim_absen.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-6">
                                        <x-input-select title="Tanggal Presensi" name="tanggal_absen" class="tanggal_absen"></x-input-select>
                                        <small class="text-primary">* h-2 presensi</small><br>
                                        <small class="text-primary">* jika presensi berada di range sppd/izin/cuti maka presensi tidak akan ada disini</small>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Jam Masuk</label>
                                        <input type="time" name="jam_masuk" class=" form-control" value="{{ old('jam_masuk') }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Jam Keluar</label>
                                        <input type="time" name="jam_keluar" class=" form-control" value="{{ old('jam_keluar') }}">
                                    </div>
                                    <div class="col-12">
                                        <x-text title="Tujuan" name="tujuan" class="tujuan" default="{{ old('tujuan') }}"/>
                                    </div>
                                    <div class="col-12">
                                        <x-input-file title="Dokumen" name="dokumen" default="{{ old('dokumen') }}" accept=".pdf,image/jpg,image/jpeg,image/png,,image/bmp"/>
                                        <small class="text-primary">* PDF dan Gambar yang boleh diupload</small><br>
                                        <small class="text-primary">* Maksimal 10Mb</small>
                                    </div>
                                </div>
                                <input type="submit" name="submit" class="btn btn-primary mt-3" value="submit">
                            </form>
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
            load_dropdown('.tanggal_absen', null, `{{ route('select2.Presensi.List') }}?nidn={{Session::get('nidn')}}&nip={{Session::get('nip')}}`, "{{ old('tanggal_absen') }}", '-- Pilih Tanggal Presensi --');
        });
    </script>
@endpush