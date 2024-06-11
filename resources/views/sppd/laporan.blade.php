@extends('template.index')
 
@section('page-title')
    <x-page-title title="SPPD">
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('sppd.index') }}">SPPD</a></li>
            <li class="breadcrumb-item active">Laporan Kegiatan</li>
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
                                <h4 class="text-primary">Form Pengajuan SPPD</h4>
                                <div class="row">
                                    <div class="col-4">
                                        <x-input-text title="Tanggal Berangkat" name="tanggal_berangkat" class="tanggal_berangkat" default="{{ old('tanggal_berangkat',$SPPD->GetTanggalBerangkat()->toFormat(FormatDate::Default)) }}" :disable="true"/>
                                    </div>
                                    <div class="col-4">
                                        <x-input-text title="Tanggal Kembali" name="tanggal_kembali" class="tanggal_kembali" default="{{ old('tanggal_kembali',$SPPD->GetTanggalkembali()->toFormat(FormatDate::Default)) }}" :disable="true"/>
                                    </div>
                                    <div class="col-4">
                                        <x-input-select title="Jenis SPPD" name="jenis_sppd" class="jenis_sppd" :disable="true"></x-input-select>
                                    </div>
                                    <div class="col-12">
                                        <x-text title="Tujuan" name="tujuan" class="tujuan" default="{{ old('tujuan',$SPPD->GetTujuan()) }}" :disable="true"/>
                                    </div>
                                    <div class="col-12">
                                        <x-text title="Keterangan" name="keterangan" class="keterangan" default="{{ old('keterangan',$SPPD->GetKeterangan()) }}" :disable="true"/>
                                    </div>
                                    <div class="col-12">
                                        @error('anggota')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <table id="tbAnggota" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>NIDN/NIP</th>
                                                    <th>Nama</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ( !is_null(old('anggota',$listAnggota)) && is_array(old('anggota',$listAnggota)) )
                                                    @foreach (old('anggota',$listAnggota) as $key => $value)
                                                    <tr data-id="{{$key}}">
                                                        <td>
                                                            <input type="hidden" class="pe-none" name="anggota[{{ $loop->index }}][nidn]" value="{{ $value['nidn'] }}" disabled="true"/>
                                                            <input type="hidden" class="pe-none" name="anggota[{{ $loop->index }}][nip]" value="{{ $value['nip'] }}" disabled="true"/>
                                                            {{ $value['nidn']??$value['nip'] }}
                                                        </td>
                                                        <td><input type="hidden" class="pe-none" name="anggota[{{ $loop->index }}][nama]" value="{{ $value['nama'] }}" disabled="true"/> {{ $value['nama'] }}</td>
                                                    </tr>  
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-primary">Laporan Kegiatan</h4>
                            <form action="{{ route('sppd.save_laporan') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" class="@error('id') is-invalid @enderror" value="{{ old('id',$SPPD->GetId()) }}">
                                <div class="row">
                                    <div class="col-12">
                                        <x-text title="Intisari / ringkasan kegiatan" name="intisari" class="intisari" default="{{ old('intisari',$SPPD?->GetIntisari()) }}"/>
                                    </div>
                                    <div class="col-12">
                                        <x-text title="Kontribusi pada Unit / Fakultas / Universitas" name="kontribusi_ufu" class="kontribusi_ufu" default="{{ old('kontribusi_ufu',$SPPD?->GetKontribusi()) }}"/>
                                    </div>
                                    <div class="col-6">
                                        <x-input-text title="Rencana tindak lanjut" name="rencana_tindak_lanjut" class="rencana_tindak_lanjut" default="{{ old('rencana_tindak_lanjut',$SPPD?->GetRencanaTindakLanjut()) }}"/>
                                    </div>
                                    <div class="col-6">
                                        <x-input-text title="Rencana waktu pelaksanaan tindak lanjut" name="rencana_waktu_tindak_lanjut" class="rencana_waktu_tindak_lanjut" default="{{ old('rencana_waktu_tindak_lanjut',$SPPD?->GetRencanaWaktuTindakLanjut()) }}"/>
                                    </div>
                                    <div class="col-12">
                                        <x-input-file title="Foto kegiatan" name="foto_kegiatan[]" multi="true" :default="$SPPD?->GetFotoKegiatan()??old('foto_kegiatan')" accept=".pdf,image/jpg,image/jpeg,image/png,image/bmp"/>
                                        <small class="text-primary">* PDF dan Gambar yang boleh diupload</small><br>
                                        <small class="text-primary">* Maksimal 10Mb</small>
                                    </div>
                                    <div class="col-12">
                                        <x-input-file title="Undangan" name="undangan[]" multi="true" :default="$SPPD?->GetUndangan()??old('undangan')" accept=".pdf,image/jpg,image/jpeg,image/png,image/bmp"/>
                                        <small class="text-primary">* PDF dan Gambar yang boleh diupload</small><br>
                                        <small class="text-primary">* Maksimal 10Mb</small>
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
            var CSRF_TOKEN      = $('meta[name="csrf-token"]').attr('content');
            load_dropdown('.jenis_sppd', null, `{{ route('select2.JenisSPPD.List') }}`, "{{ old('jenis_sppd',$SPPD->GetJenisSPPD()?->GetId()) }}", '-- Pilih Jenis SPPD --');
            load_dropdown('.nidnnipAddAnggota', null, `{{ route('select2.DosenPegawai.List') }}`, "{{ old('nidn_nip') }}", '-- Pilih Nama --','#modalAddAnggota');

            $('.rencana_waktu_tindak_lanjut').datepicker({
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

            String.prototype.isEmpty = function() {
                return (this.length === 0 || !this.trim());
            };

            const createTableRowStrategy = {
                anggota: function(row,length,response,btnClassName) {
                    let nidn = response?.data?.dosen?.nidn??"";
                    let nip = response?.data?.pegawai?.nip??"";
                    let reff = null;
                    if(response?.data?.dosen!=null){
                        reff = nidn
                    } else if(response?.data?.pegawai!=null){
                        reff=nip
                    }
                    let nama_lengkap = response?.data?.dosen?.nama_dosen ?? response?.data?.pegawai?.nama;

                    row.setAttribute('data-id', length);
                    row.insertCell(0).innerHTML = ` <input type="hidden" class="pe-none" name="anggota[${length}][nidn]" value="${nidn}"/>
                                                    <input type="hidden" class="pe-none" name="anggota[${length}][nip]" value="${nip}"/> ${reff}`;
                    // row.insertCell(1).innerHTML = ` ${nip}`;
                    row.insertCell(1).innerHTML = `<input type="hidden" class="pe-none" name="anggota[${length}][nama]" value="${nama_lengkap}"/> ${nama_lengkap}`;
                    return row;
                }
            };
            function createTableRow(tbody, strategyName, response, btnClassName) {
                const length = tbody.rows.length;
                const row = tbody.insertRow();

                createTableRowStrategy[strategyName](row, length, response, btnClassName);
            }
            //optimal

            function preventEnterKey(element) {
                element.on('keypress', function(event) {
                    if (event.which === 13) {
                        event.preventDefault();
                    }
                });
            }
        });
    </script>
@endpush