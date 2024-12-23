@extends('template.index')
 
@section('page-title')
    <x-page-title title="Slip Gaji">
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Slip Gaji</li>
            </ol>
        </nav>
    </x-page-title>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body row gap-1">
                        <div class="col-3">
                            <x-input-select title="Tahun" name="tahun" class="tahun"></x-input-select>
                        </div>
                        <div class="col-3">
                            <x-input-select title="Bulan" name="bulan" class="bulan"></x-input-select>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary mt-2 btn_show">Tampilkan</button>
                            <button onclick="printDiv('printMe')" class="d-none btn btn-success mt-2 btn-print">
                                <i class="fa fa-print"></i> 
                                <b>Print</b>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 slip_gaji" id="printMe">
                
            </div>
        </div>
    </div>
</div>
@stop

@push('scripts')
    <script type="text/javascript" src="{{ Utility::loadAsset('my.js') }}"></script>
    <script type="text/javascript" src="{{ Utility::loadAsset('pattern.js') }}"></script>
    <script>
        function printDiv(divName) {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
        }
        $(document).ready(function () {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            
            const nidn = `{{Session::get('nidn')}}`
            const nip = `{{$nip}}`
            let input_tahun = null;
            let input_bulan = null;
            
            const bulan = [
                {
                    "id":"01",
                    "text":"Januari"
                },
                {
                    "id":"02",
                    "text":"Februari"
                },
                {
                    "id":"03",
                    "text":"Maret"
                },
                {
                    "id":"04",
                    "text":"April"
                },
                {
                    "id":"05",
                    "text":"Mei"
                },
                {
                    "id":"06",
                    "text":"Juni"
                },
                {
                    "id":"07",
                    "text":"Juli"
                },
                {
                    "id":"08",
                    "text":"Agustus"
                },
                {
                    "id":"09",
                    "text":"September"
                },
                {
                    "id":"10",
                    "text":"Oktober"
                },
                {
                    "id":"11",
                    "text":"November"
                },
                {
                    "id":"12",
                    "text":"Desember"
                },
            ];
            load_dropdown('.tahun', {!! $tahun !!}, null, null, '-- Pilih Tahun --');
            load_dropdown('.bulan', bulan, null, null, '-- Pilih Bulan --');

            $('.tahun').on('select2:select', function(e) {
                // var data = e.params.data;
                input_tahun = $(this).val()
            });
            $('.bulan').on('select2:select', function(e) {
                // var data = e.params.data;
                input_bulan = $(this).val()
            });
            $('.btn_show').click(function(e){
                e.preventDefault();

                var data = new FormData();    
                data.append('nip', nip);
                data.append('tahun', input_tahun);
                data.append('bulan', input_bulan);

                $.ajax({
                    url: `{{route('api.slip_gaji.index')}}`,
                    data: data,
                    contentType: 'application/json',
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    beforeSend: function(){
                        $(`.slip_gaji`).html(`loading...`);
                        $('.btn-print').addClass('d-none');
                    },
                    success: function(response){
                        console.log(response);
                        $(`.slip_gaji`).html(``);

                        if(response.status=="ok"){
                            $(`.slip_gaji`).html(``);
                            let factory = new SlipGajiFactory();
                            let slipGaji = factory.createShape($(`.slip_gaji`),true,response,(nidn!=null||nidn!=""? "dosen":"pegawai"));
                            slipGaji.draw();
                            $('.btn-print').removeClass('d-none');
                        } else{
                            $(`.slip_gaji`).html(`tidak ada data`);
                        }
                    },
                    error: function(xhr, status, error){
                        const err = handleAjaxError(xhr, status, error, false, `{{route('api.slip_gaji.index')}}`);
                        $(`.slip_gaji`).html(err);
                    },
                    complete: function(){

                    }
                });
            });
        });
    </script>
@endpush