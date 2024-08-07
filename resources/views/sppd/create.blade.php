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
                                    <div class="col-12">
                                        <x-input-select title="Sarana Transportasi" name="sarana_transportasi" class="sarana_transportasi"></x-input-select>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <a href="#" class="btn btn-primary btnModalAddAnggota">Tambah</a>
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
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ( !is_null(old('anggota')) && is_array(old('anggota')) )
                                                    @foreach (old('anggota') as $value)
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" class="pe-none" name="anggota[{{ $loop->index }}][nidn]" value="{{ $value['nidn'] }}"/>
                                                            <input type="hidden" class="pe-none" name="anggota[{{ $loop->index }}][nip]" value="{{ $value['nip'] }}"/>
                                                            {{ $value['nidn']??$value['nip'] }}
                                                        </td>
                                                        <td><input type="hidden" class="pe-none" name="anggota[{{ $loop->index }}][nama]" value="{{ $value['nama'] }}"/>{{ $value['nama'] }}</td>
                                                        <td><a href="#" class="btn btn-danger btnHapusAnggota">Hapus</a></td>
                                                    </tr>  
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
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

    <div class="modal fade" id="modalAddAnggota" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Anggota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <x-input-select title="Nama" name="nidn_nip" class="nidnnipAddAnggota" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btnAddAnggota">Simpan</button>
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

            const transport = [
                {
                    id:"Operasional Unpak",
                    text:"Operasional Unpak",
                },
                {
                    id:"Kendaraan Pribadi",
                    text:"Kendaraan Pribadi",
                },
                {
                    id:"Transportasi Umum",
                    text:"Transportasi Umum",
                },
            ];
            load_dropdown('.sarana_transportasi', transport, null, "{{ old('sarana_transportasi') }}", '-- Pilih Sarana Transportasi --');
            load_dropdown('.jenis_sppd', null, `{{ route('select2.JenisSPPD.List') }}`, "{{ old('jenis_sppd') }}", '-- Pilih Jenis SPPD --');
            load_dropdown('.nidnnipAddAnggota', null, `{{ route('select2.DosenPegawai.List') }}`, "{{ old('nidn_nip') }}", '-- Pilih Nama --','#modalAddAnggota');
            load_dropdown('.verifikasi', null, `{{ route('select2.PegawaiV2.List') }}?struktural=verifikator`, "{{ old('verifikasi') }}", '-- Pilih Nama Atasan --');
            
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

            let modalAddAnggota        = new bootstrap.Modal(document.getElementById('modalAddAnggota'));
            let nidnnipAddAnggota      = $('.nidnnipAddAnggota');
            let btnAddAnggota          = $('.btnAddAnggota');
            let btnModalAddAnggota     = $('.btnModalAddAnggota');
            let tbAnggota              = document.getElementById('tbAnggota');

            preventEnterKey(nidnnipAddAnggota);

            $(document).on('shown.bs.modal', '#modalAddAnggota', function() {
                nidnnipAddAnggota.focus().val("");
            });
            $(document).on('hidden.bs.modal', '#modalAddAnggota', function() {
                nidnnipAddAnggota.blur().val("");
            });
            String.prototype.isEmpty = function() {
                return (this.length === 0 || !this.trim());
            };

            //optimal
            const deleteStrategy = {
                hapusAnggota: function(element) {
                    deleteAndReindexRow(element, tbAnggota);
                },
            };
            function handleDeleteButtonClick(e) {
                e.preventDefault();
                const target = $(this);
                let strategyName = null;
                if(target.hasClass("btnHapusAnggota")){
                    strategyName = 'hapusAnggota';
                }

                if(strategyName!==null) deleteStrategy[strategyName](target);
            }
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
                    row.insertCell(2).innerHTML = `<a href="#" class="btn btn-danger ${btnClassName}">Hapus</a>`;
                    return row;
                }
            };
            function createTableRow(tbody, strategyName, response, btnClassName) {
                const length = tbody.rows.length;
                const row = tbody.insertRow();

                createTableRowStrategy[strategyName](row, length, response, btnClassName);
            }
            //optimal

            function hapusAnggota(e) {
                e.preventDefault()
                deleteAndReindexRow($(this), tbAnggota);
            }

            function preventEnterKey(element) {
                element.on('keypress', function(event) {
                    if (event.which === 13) {
                        event.preventDefault();
                    }
                });
            }

            function deleteAndReindexRow(targetButton, table) {
                var button = targetButton;
                var row = button.closest('tr');
                var dataId = row.data('id');
                row.remove();

                const tbody = table.getElementsByTagName('tbody')[0];
                const rows = tbody.getElementsByTagName('tr');

                for (let i = dataId; i < rows.length; i++) {
                    rows[i].setAttribute('data-id', i);
                    const inputs = rows[i].querySelectorAll('input[type="hidden"]');
                    inputs.forEach(input => {
                        const name = input.getAttribute('name').replace(/\[\d+\]/, `[${i}]`);
                        input.setAttribute('name', name);
                    });
                }
            }

            $(document).on('click', '#tbAnggota .btnHapusAnggota', handleDeleteButtonClick);

            btnModalAddAnggota.on('click', function(e) {
                e.preventDefault();
                modalAddAnggota.show();
            });

            btnAddAnggota.on('click', function(e) {
                e.preventDefault();
                if(nidnnipAddAnggota.select2('data')==0){
                    alert("wajib pilih anggota")
                } else if(nidnnipAddAnggota.select2('data')>1){
                    alert("ada masalah pada aplikasi")
                } else{
                    const detail = nidnnipAddAnggota.select2('data')[0];
                    const id    = detail['id'];
                    const type  = detail['type'];
                    let nidn    = type=="dosen"? id:null;
                    let nip     = type=="pegawai"? id:null;
                    let nama    = detail.text;

                    let dataForm = new FormData();
                    dataForm.append("X-CSRF-TOKEN",CSRF_TOKEN);
                    dataForm.append("nidn",nidn);
                    dataForm.append("nip",nip);
                    console.log({'X-CSRF-TOKEN': CSRF_TOKEN, 'nidn':nidn, 'nip':nip});

                    if(isDuplicatValueDynamicInput("anggota","nidn",nidn)){
                        alert(`${nama} sudah dimasukkan sebelumnya`);
                    } else if(isDuplicatValueDynamicInput("anggota","nip",nip)){
                        alert(`${nama} sudah dimasukkan sebelumnya`);
                    } 
                    // else if(nidn != null && nip != null){
                    //     alert(`nidn dan nip tidak boleh diinput bersamaan`);
                    // } 
                    else if(nidn == null && nip == null){
                        alert(`nama yg harus diisi`);
                    } else{
                        $('.btnAddAnggota').attr('disabled', true);

                        $.ajax({
                            url: "{{ route('api.Info.InfoDosenPegawai') }}",
                            method: 'POST',
                            data: dataForm,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                console.log(response)
                                $('.btnAddAnggota').removeAttr("disabled");
                                modalAddAnggota.hide();
                                if (response.status == "ok") {
                                    const tbody = tbAnggota.getElementsByTagName('tbody')[0];
                                    createTableRow(tbody, 'anggota', response, 'btnHapusAnggota');
                                } else {
                                    alert(response.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                handleAjaxError(xhr, status, error)
                                $('.btnAddAnggota').removeAttr("disabled");
                                modalAddAnggota.hide();
                            }
                        });
                    }
                }
                $('.nidnnipAddAnggota').val("").trigger("change");
            });
        });
    </script>
@endpush