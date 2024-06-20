@extends('template.index')
 
@section('page-title')
    <x-page-title title="Izin">
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('izin.index') }}">Izin</a></li>
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
                            <form action="{{ route('izin.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-6">
                                        <x-input-text title="Tanggal Izin" name="tanggal_pengajuan" class="tanggal_pengajuan" default="{{ old('tanggal_pengajuan') }}"/>
                                    </div>
                                    <div class="col-6">
                                        <x-input-select title="Jenis Izin" name="jenis_izin" class="jenis_izin"></x-input-select>
                                    </div>
                                    <div class="col-12">
                                        <x-text title="Tujuan" name="tujuan" class="tujuan" default="{{ old('tujuan') }}"/>
                                    </div>
                                    <div class="col-12">
                                        <x-input-file title="Dokumen" name="dokumen" default="{{ old('dokumen') }}" accept=".pdf,image/jpg,image/jpeg,image/png,,image/bmp"/>
                                        <small class="text-primary">* PDF dan Gambar yang boleh diupload</small><br>
                                        <small class="text-primary">* Maksimal 10Mb</small>
                                    </div>
                                    <div class="col-12">
                                        <x-input-select title="Verifikasi Atasan" name="verifikasi" class="verifikasi"></x-input-select>
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
    <script type="text/javascript" src="{{ Utility::loadAsset('my.js') }}"></script>
    <script>
        $(document).ready(function () {
            load_dropdown('.jenis_izin', null, `{{ route('select2.JenisIzin.List') }}`, "{{ old('jenis_izin') }}", '-- Pilih Jenis Izin --');
            load_dropdown('.verifikasi', null, `{{ route('select2.PegawaiV2.List') }}?struktural=struktural_only`, "{{ old('verifikasi') }}", '-- Pilih Nama Atasan --');

            $('.tanggal_pengajuan').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlidht: true,
                orientation: 'bottom',
                datesDisabled:[],
                daysOfWeekDisabled:[0],
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

            $('.tanggal_pengajuan').datepicker('setStartDate', new Date());
            $('.tanggal_pengajuan').datepicker('setEndDate', new Date());
        });
    </script>
@endpush