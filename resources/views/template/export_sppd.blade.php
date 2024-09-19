<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    .styled-table {
        border-collapse: collapse;
        margin: 10px 0 0 0;
        font-size: 0.9em;
        font-family: sans-serif;
        min-width: 100%;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    }

    .styled-table thead tr {
        background-color: #009879;
        color: #ffffff;
        text-align: left;
    }

    .styled-table th,
    .styled-table td {
        padding: 6px 8px;
        border: 1px solid #000;
    }

    .styled-table tbody tr {
        border-bottom: 1px solid #000;
    }

    .styled-table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
    }

    .styled-table tbody tr.active-row {
        font-weight: bold;
        color: #009879;
    }
    .paraf{
        display: flex;
        justify-content: flex-end;
        width: 100%;

        .paraf__container{
            flex-grow: 1;
            width: 30%;
            /* text-align: center; */
        }
    }
    .page-break {
        /* page-break-after: always; */
    }
    .page-break:last-child {
        /* page-break-after: avoid; */
    }
</style>
<body>
    @foreach($list_sppd as $key => $sppd)
        <div class="page-break">
        <img src="{{ Utility::loadAsset('assets/img/KOP UNPAK.jpg') }}" alt="logo" style="width: 100% !important;">
        <br>
        <table class="styled-table">
            <tr>
                <td>1</td>
                <td>Nama Dosen / Pegawai yang melakukan perjalanan dinas</td>
                <td>
                    @if (!empty($sppd->Dosen))
                        {{$sppd->Dosen->NIDN}} - {{$sppd->Dosen->nama_dosen}}
                    @elseif (!empty($sppd->Pegawai))
                        {{$sppd->Pegawai->nip}} - {{$sppd->Pegawai->nama}}
                    @endif
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Jenis Dinas</td>
                <td>{{$sppd->JenisSPPD?->nama??"NA"}}</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Maksud perjalanan dinas</td>
                <td>{{$sppd->tujuan}}</td>
            </tr>
            <tr>
                <td>4</td>
                <td>Sarana Transportasi</td>
                <td>{{$sppd->sarana_transportasi}}</td>
            </tr>
            <tr>
                <td></td>
                <td>1. Tanggal berangkat</td>
                <td>{{ empty($sppd->tanggal_berangkat)? "NA":Carbon::parse($sppd->tanggal_berangkat)->setTimezone('Asia/Jakarta')->format("d F Y") }}</td>
            </tr>
            <tr>
                <td></td>
                <td>2. Tanggal harus kembali</td>
                <td>{{ empty($sppd->tanggal_kembali)? "NA":Carbon::parse($sppd->tanggal_kembali)->setTimezone('Asia/Jakarta')->format("d F Y") }}</td>
            </tr>
            <tr>
                <td>5</td>
                <td colspan="2">Pengikut</td>
            </tr>
            @foreach ($sppd->anggota as $i => $anggota)
                @if (!empty($anggota->Dosen))
                <tr>
                    <td></td>
                    <td colspan="2">{{ $i+1 }}. {{$anggota->Dosen->NIDN}} - {{$anggota->Dosen->nama_dosen}}</td>
                </tr>
                @endif

                @if (!empty($anggota->Pegawai))
                <tr>
                    <td></td>
                    <td colspan="2">{{ $i+1 }}. {{$anggota->Pegawai->nip}} - {{$anggota->Pegawai->nama}}</td>
                </tr>
                @endif
            @endforeach
            <tr>
                <td>6</td>
                <td>Keterangan</td>
                <td>{{$sppd->keterangan}}</td>
            </tr>
            <tr style="border: 0px solid white !important; background: white">
                <td colspan="2" style="border: 0px solid white !important; background: white"></td>
                <td style="border: 0px solid white !important; background: white">
                    <center>
                        <p>Menyetujui</p>
                        @if (!empty($sppd->EPribadi) && $sppd->status=="terima sdm")
                        <img src='data:image/png;base64, {!! base64_encode(QrCode::size(100)->generate("Nama : ".$sppd->EPribadi->nama)) !!}' alt="tanda tangan"/>
                        <br>
                        @else
                        <br>
                        <br>
                        @endif
                        <b>
                            {{$sppd->EPribadi?->nama??"Tidak diketahui"}}<br>
                            {{$sppd->EPribadi?->nip??"Tidak diketahui"}}
                        </b>
                    </center>
                </td>
            </tr>
        </table>
        <br>
        <br>
        <br>
        <br>
        <table style="padding: 0px">
            @php
                $list_anggota = collect($sppd->AnggotaFlat)->chunk(3);
            @endphp

            @foreach($list_anggota as $rows)
                @foreach($rows as $anggota)
                    @if ($loop->first)
                        <tr style="border: 1px solid black !important">
                            <td style="border: 1px solid black !important; width: 225px;">
                                <center>
                                <p>Tanggal {{ $anggota->tanggal }}</p>
                                <br>
                                <br>
                                <br>
                                <b>{{ $anggota->nama }}</b><br>
                                {{ $anggota->kodePengenal }}
                                </center>     
                            </td>
                    @elseif ($loop->last)
                            <td style="border: 1px solid black !important; width: 225px;">
                                <center>
                                <p>Tanggal {{ $anggota->tanggal }}</p>
                                <br>
                                <br>
                                <br>
                                <b>{{ $anggota->nama }}</b><br>
                                {{ $anggota->kodePengenal }}
                                </center>     
                            </td>
                        </tr>
                    @else
                            <td style="border: 1px solid black !important; width: 225px;">
                                <center>
                                <p>Tanggal {{ $anggota->tanggal }}</p>
                                <br>
                                <br>
                                <br>
                                <b>{{ $anggota->nama }}</b><br>
                                {{ $anggota->kodePengenal }}
                                </center>     
                            </td>
                    @endif
                @endforeach
            @endforeach

        </table>
        <br>
        <!-- <table class="styled-table">
            <tr>
                <td colspan="3">
                    <h2 align="center">Laporan Kegiatan</h2>
                </td>
            </tr>
            <tr>
                <td>Intisari / ringkasan kegiatan</td>
                <td>:</td>
                <td>{{$sppd->intisari}}</td>
            </tr>
            <tr>
                <td>Kontribusi pada Unit / Fakultas / Universitas</td>
                <td>:</td>
                <td>{{$sppd->kontribusi}}</td>
            </tr>
            <tr>
                <td>Rencana tindak lanjut</td>
                <td>:</td>
                <td>{{$sppd->rencana_tindak_lanjut}}</td>
            </tr>
            <tr>
                <td>Rencana waktu pelaksanaan tindak lanjut</td>
                <td>:</td>
                <td>{{!empty($sppd->rencana_waktu_tindak_lanjut)? date('l, d F Y',strtotime($sppd->rencana_waktu_tindak_lanjut)):''}}</td>
            </tr>
            <tr>
                <td>Foto</td>
                <td>:</td>
                <td>
                    <ol>
                        @foreach ($sppd->FileLaporan->where('type','foto_kegiatan')->values() as $foto)
                        <li>{{ Utility::loadAsset('dokumen_laporan_sppd/'.rawurlencode($foto->file)) }}</li>
                        @endforeach
                    </ol>
                </td>
            </tr><tr>
                <td>Undangan</td>
                <td>:</td>
                <td>
                    <ol>
                        @foreach ($sppd->FileLaporan->where('type','undangan')->values() as $foto)
                        <li>{{ Utility::loadAsset('dokumen_laporan_sppd/'.rawurlencode($foto->file)) }}</li>
                        @endforeach
                    </ol>
                </td>
            </tr>
        </table> -->
    </div>    
    @endforeach
</body>
</html>