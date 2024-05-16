@extends('template.index')
 
@section('page-title')
    <x-page-title title="SPPD">
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('sppd.index') }}">SPPD</a></li>
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
                            <form action="{{ route('sppd.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-4">
                                        <x-input-text title="Tanggal Berangkat" name="tanggal_berangkat" class="tanggal_berangkat" default="{{ old('tanggal_berangkat') }}"/>
                                    </div>
                                    <div class="col-4">
                                        <x-input-text title="Tanggal Kembali" name="tanggal_kembali" class="tanggal_kembali" default="{{ old('tanggal_kembali') }}"/>
                                    </div>
                                    <div class="col-4">
                                        <x-input-select title="Jenis SPPD" name="jenis_sppd" class="jenis_sppd"></x-input-select>
                                    </div>
                                    <div class="col-12">
                                        <x-text title="Tujuan" name="tujuan" class="tujuan" default="{{ old('tujuan') }}"/>
                                    </div>
                                    <div class="col-12">
                                        <x-text title="Keterangan" name="keterangan" class="keterangan" default="{{ old('keterangan') }}"/>
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
            load_dropdown('.jenis_sppd', null, `{{ route('select2.JenisSPPD.List') }}`, "{{ old('jenis_sppd') }}", '-- Pilih Jenis SPPD --');

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
                $('.tanggal_kembali').datepicker('setStartDate', min);
            });
        });
    </script>
@endpush