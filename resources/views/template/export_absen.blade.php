<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    :root{--bs-blue:#0d6efd;--bs-indigo:#6610f2;--bs-purple:#6f42c1;--bs-pink:#d63384;--bs-red:#dc3545;--bs-orange:#fd7e14;--bs-yellow:#ffc107;--bs-green:#198754;--bs-teal:#20c997;--bs-cyan:#0dcaf0;--bs-white:#fff;--bs-gray:#6c757d;--bs-gray-dark:#343a40;--bs-primary:#0d6efd;--bs-secondary:#6c757d;--bs-success:#198754;--bs-info:#0dcaf0;--bs-warning:#ffc107;--bs-danger:#dc3545;--bs-light:#f8f9fa;--bs-dark:#212529;--bs-font-sans-serif:system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans","Liberation Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";--bs-font-monospace:SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;--bs-gradient:linear-gradient(180deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0))}*,::after,::before{box-sizing:border-box}@media (prefers-reduced-motion:no-preference){:root{scroll-behavior:smooth}}body{margin:0;font-family:var(--bs-font-sans-serif);font-size:1rem;font-weight:400;line-height:1.5;color:#212529;background-color:#fff;-webkit-text-size-adjust:100%;-webkit-tap-highlight-color:transparent}table{caption-side:bottom;border-collapse:collapse}th{text-align:inherit;text-align:-webkit-match-parent}tbody,td,th,thead,tr{border-color:inherit;border-style:solid;border-width:0}button:focus:not(:focus-visible){outline:0}[type=button]:not(:disabled),[type=reset]:not(:disabled),[type=submit]:not(:disabled),button:not(:disabled){cursor:pointer}::-moz-focus-inner{padding:0;border-style:none}::-webkit-datetime-edit-day-field,::-webkit-datetime-edit-fields-wrapper,::-webkit-datetime-edit-hour-field,::-webkit-datetime-edit-minute,::-webkit-datetime-edit-month-field,::-webkit-datetime-edit-text,::-webkit-datetime-edit-year-field{padding:0}::-webkit-inner-spin-button{height:auto}::-webkit-search-decoration{-webkit-appearance:none}::-webkit-color-swatch-wrapper{padding:0}::file-selector-button{font:inherit}::-webkit-file-upload-button{font:inherit;-webkit-appearance:button}.row{--bs-gutter-x:1.5rem;--bs-gutter-y:0;display:flex;flex-wrap:wrap;margin-top:calc(var(--bs-gutter-y) * -1);margin-right:calc(var(--bs-gutter-x) * -.5);margin-left:calc(var(--bs-gutter-x) * -.5)}.row>*{flex-shrink:0;width:100%;max-width:100%;padding-right:calc(var(--bs-gutter-x) * .5);padding-left:calc(var(--bs-gutter-x) * .5);margin-top:var(--bs-gutter-y)}.col-12{flex:0 0 auto;width:100%}.table{--bs-table-bg:transparent;--bs-table-accent-bg:transparent;--bs-table-striped-color:#212529;--bs-table-striped-bg:rgba(0, 0, 0, 0.05);--bs-table-active-color:#212529;--bs-table-active-bg:rgba(0, 0, 0, 0.1);--bs-table-hover-color:#212529;--bs-table-hover-bg:rgba(0, 0, 0, 0.075);width:100%;margin-bottom:1rem;color:#212529;vertical-align:top;border-color:#dee2e6}.table>:not(caption)>*>*{padding:.5rem .5rem;background-color:var(--bs-table-bg);border-bottom-width:1px;box-shadow:inset 0 0 0 9999px var(--bs-table-accent-bg)}.table>tbody{vertical-align:inherit}.table>thead{vertical-align:bottom}.table>:not(:last-child)>:last-child>*{border-bottom-color:currentColor}.table-striped>tbody>tr:nth-of-type(odd){--bs-table-accent-bg:var(--bs-table-striped-bg);color:var(--bs-table-striped-color)}.form-control[type=file]:not(:disabled):not([readonly]){cursor:pointer}.form-control::file-selector-button{padding:.375rem .75rem;margin:-.375rem -.75rem;-webkit-margin-end:.75rem;margin-inline-end:.75rem;color:#212529;background-color:#e9ecef;pointer-events:none;border-color:inherit;border-style:solid;border-width:0;border-inline-end-width:1px;border-radius:0;transition:color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out}@media (prefers-reduced-motion:reduce){.form-control::file-selector-button{transition:none}}.form-control:hover:not(:disabled):not([readonly])::file-selector-button{background-color:#dde0e3}.form-control:hover:not(:disabled):not([readonly])::-webkit-file-upload-button{background-color:#dde0e3}.form-control-sm::file-selector-button{padding:.25rem .5rem;margin:-.25rem -.5rem;-webkit-margin-end:.5rem;margin-inline-end:.5rem}.form-control-lg::file-selector-button{padding:.5rem 1rem;margin:-.5rem -1rem;-webkit-margin-end:1rem;margin-inline-end:1rem}.form-control-color:not(:disabled):not([readonly]){cursor:pointer}.form-floating>.form-control:not(:-moz-placeholder-shown){padding-top:1.625rem;padding-bottom:.625rem}.form-floating>.form-control:not(:-moz-placeholder-shown)~label{opacity:.65;transform:scale(.85) translateY(-.5rem) translateX(.15rem)}.card{position:relative;display:flex;flex-direction:column;min-width:0;word-wrap:break-word;background-color:#fff;background-clip:border-box;border:1px solid rgba(0,0,0,.125);border-radius:.25rem}.card-body{flex:1 1 auto;padding:1rem 1rem}.badge{display:inline-block;padding:.35em .65em;font-size:.75em;font-weight:700;line-height:1;color:#fff;text-align:center;white-space:nowrap;vertical-align:baseline;border-radius:.25rem}.badge:empty{display:none}.visually-hidden-focusable:not(:focus):not(:focus-within){position:absolute!important;width:1px!important;height:1px!important;padding:0!important;margin:-1px!important;overflow:hidden!important;clip:rect(0,0,0,0)!important;white-space:nowrap!important;border:0!important}.text-center{text-align:center!important}.bg-success{background-color:#198754!important}.bg-warning{background-color:#ffc107!important}.bg-danger{background-color:#dc3545!important}
</style>
<body>
    <div class="card">
        <div class="card-body row">
            <div class="col-12">
                <h5>Per Tanggal {{ $start }} - {{ $end }}</h5>
            </div>
            <div class="col-12 table-responsive">
                <table id="tb" class="table table-striped text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            @foreach ($list_tanggal as $tanggal)
                                <th>{{ Carbon::parse($tanggal)->setTimezone('Asia/Jakarta')->format('d') }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list_data as $data)
                            @php
                                $nama = $data['type']=="pegawai"? $data['pengguna']['nama']:$data['pengguna']['nama_dosen']; 
                                $kode = $data['type']=="pegawai"? $data['pengguna']['nip']:$data['pengguna']['NIDN']; 
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$nama}} - {{$kode}}</td>
                                @foreach ($list_tanggal as $tanggal)
                                    <td>
                                        <table>
                                            <tr>
                                                <td class="column_min">
                                                    @php
                                                        $aturan_jam = "08:00 - 15:00";
                                                        $dayOfWeek = Carbon::parse($tanggal)->setTimezone('Asia/Jakarta')->dayOfWeek;
                                                        if ($dayOfWeek == Carbon::FRIDAY) {
                                                            $aturan_jam = "08:00 - 14:00";
                                                        } elseif ($dayOfWeek == Carbon::SATURDAY) {
                                                            $aturan_jam = "08:00 - 12:00";
                                                        }
                                                    @endphp
                                                    {{ $aturan_jam }}
                                                </td>
                                            </tr>
                                            @php
                                                $keterangan = "";
                                                $dataDetail = $data[$tanggal];
                                                foreach($dataDetail as $detail){
                                                    $info = $detail->info;
                                                    switch ($info->type) {
                                                        case 'absen':
                                                            if (empty($info?->keterangan['masuk']) && empty($info?->keterangan['keluar'])) {
                                                                $keterangan = "<span class='badge bg-danger'>Tidak Masuk</span>";
                                                            } elseif (!empty($info?->keterangan['masuk']) && empty($info?->keterangan['keluar'])) {
                                                                $masuk = Carbon::parse($info?->keterangan['masuk'])->setTimezone('Asia/Jakarta')->format('H:i');
                                                                $keterangan = "<span class='badge bg-success'>".$masuk."</span> - <span class='badge bg-danger'>Masih Masuk</span>";
                                                            } else {
                                                                $masuk = Carbon::parse($info?->keterangan['masuk'])->setTimezone('Asia/Jakarta')->format('H:i');
                                                                $keluar = Carbon::parse($info?->keterangan['keluar'])->setTimezone('Asia/Jakarta')->format('H:i');
                                                                $keterangan = "<span class='badge bg-success'>".$masuk."</span> - <span class='badge bg-danger'>".$keluar."</span>";
                                                            }
                                                            break;
                                                        case 'izin':
                                                            $keterangan = "<span class='badge bg-primary'>Izin</span>";
                                                            break;
                                                        case 'cuti':
                                                            $keterangan = "<span class='badge bg-warning text-black'>Cuti</span>";
                                                            break;
                                                    }
                                                }
                                            @endphp
                                            <tr>
                                                <td>{!! $keterangan !!}</td>
                                            </tr>
                                        </table>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>