@extends('template.index')
 
@section('page-title')
    <x-page-title title="Cuti">
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cuti.index') }}">Cuti</a></li>
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
                            <form action="{{ route('cuti.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <x-input-select title="Jenis Cuti" name="jenis_cuti" class="jenis_cuti"></x-input-select>
                                    </div>
                                    <div class="col-6">
                                        <x-input-text title="Tanggal Mulai" name="tanggal_mulai" class="tanggal_mulai" default="{{ old('tanggal_mulai') }}"/>
                                    </div>
                                    <div class="col-6">
                                        <x-input-text title="Tanggal Akhir" name="tanggal_akhir" class="tanggal_akhir" default="{{ old('tanggal_akhir') }}"/>
                                    </div>
                                    <div class="col-12">
                                        <x-input-number title="Lama Cuti" name="lama_cuti" class="lama_cuti" default="{{ old('lama_cuti') }}"/>
                                    </div>
                                    <div class="col-12">
                                        <x-text title="Tujuan" name="tujuan" class="tujuan" default="{{ old('tujuan') }}"/>
                                    </div>
                                    <div class="col-12">
                                        <x-input-file title="Dokumen" name="dokumen" accept=".pdf,image/jpg,image/jpeg,image/png,,image/bmp"/>
                                        <small class="text-primary">* PDF dan Gambar yang boleh diupload</small><br>
                                        <small class="text-primary">* Maksimal 10Mb</small>
                                    </div>
                                    <div class="col-12">
                                        <x-input-select title="Verifikasi Atasan" name="verifikasi" class="verifikasi"></x-input-select>
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
            var CSRF_TOKEN      = $('meta[name="csrf-token"]').attr('content');

            load_dropdown('.jenis_cuti', null, `{{ route('select2.JenisCuti.List') }}`, "{{ old('jenis_cuti') }}", '-- Pilih Jenis Cuti --');
            load_dropdown('.verifikasi', null, `{{ route('select2.PegawaiV2.List') }}`, "{{ old('verifikasi') }}", '-- Pilih Nama Atasan --');

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
            $('.tanggal_mulai').datepicker('setStartDate', new Date());
            // $('.tanggal_mulai').datepicker('setDatesDisabled', ['2024-05-01']);

            $('.tanggal_mulai').change(function(e) {
                const min = $(this).val()
                $('.tanggal_akhir').datepicker('setStartDate', min);
            });
            $('.jenis_cuti').on('select2:select', function(e) {
                var data = e.params.data;
                $('.lama_cuti').prop("min",data.min).prop("max",data.max)
                console.log(data?.kondisi?.hitung_libur)

                $('.tanggal_mulai').datepicker('setDaysOfWeekDisabled', data?.kondisi?.hitung_libur??[0]);
                $('.tanggal_akhir').datepicker('setDaysOfWeekDisabled', data?.kondisi?.hitung_libur??[0]);
            });
        });
    </script>
@endpush