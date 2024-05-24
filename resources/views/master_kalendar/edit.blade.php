@extends('template.index')
 
@section('page-title')
    <x-page-title title="Master Kalendar">
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('master_kalendar.index') }}">Master Kalendar</a></li>
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
                            <form action="{{ route('master_kalendar.update') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{$MasterKalendar->GetId()}}">
                                <div class="row">
                                    <div class="col-6">
                                        <x-input-text title="Tanggal Mulai" name="tanggal_mulai" class="tanggal_mulai" default="{{ old('tanggal_mulai',$MasterKalendar->GetTanggalMulai()?->toFormat(FormatDate::Default)) }}"/>
                                    </div>
                                    <div class="col-6">
                                        <x-input-text title="Tanggal Akhir" name="tanggal_berakhir" class="tanggal_berakhir" default="{{ old('tanggal_berakhir',$MasterKalendar->GetTanggalAkhir()?->toFormat(FormatDate::Default)) }}"/>
                                    </div>
                                    <div class="col-12">
                                        <x-text title="Keterangan" name="keterangan" class="keterangan" default="{{ old('keterangan',$MasterKalendar->GetKeterangan()) }}"/>
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

            $('.tanggal_mulai').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlidht: true,
                orientation: 'bottom',
                datesDisabled: [],
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
            $('.tanggal_berakhir').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlidht: true,
                orientation: 'bottom',
                datesDisabled:[],
                daysOfWeekDisabled:[0],
                startDate:"{{old('tanggal_mulai',$MasterKalendar->GetTanggalMulai()?->toFormat(FormatDate::Default))}}",
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
            // $('.tanggal_mulai').datepicker('setDatesDisabled', ['2024-05-01']);

            $('.tanggal_mulai').change(function(e) {
                const min = $(this).val()
                $('.tanggal_berakhir').datepicker('setStartDate', min);
            });
        });
    </script>
@endpush