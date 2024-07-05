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
    <div class="page-break">
        <table class="styled-table">
            <tr>
                <td colspan="3">
                    <h2 align="center">Laporan Kegiatan</h2>
                </td>
            </tr>
            <tr>
                <td>Intisari / ringkasan kegiatan</td>
                <td>:</td>
                <td>{{$sppd->GetIntisari()}}</td>
            </tr>
            <tr>
                <td>Kontribusi pada Unit / Fakultas / Universitas</td>
                <td>:</td>
                <td>{{$sppd->GetKontribusi()}}</td>
            </tr>
            <tr>
                <td>Rencana tindak lanjut</td>
                <td>:</td>
                <td>{{$sppd->GetRencanaTindakLanjut()}}</td>
            </tr>
            <tr>
                <td>Rencana waktu pelaksanaan tindak lanjut</td>
                <td>:</td>
                <td>{{!empty($sppd->GetRencanaTindakLanjut())? date('l, d F Y',strtotime($sppd->GetRencanaWaktuTindakLanjut())):''}}</td>
            </tr>
            <tr>
                <td>Foto</td>
                <td>:</td>
                <td>
                    <ol>
                        @foreach ($sppd->GetFotoKegiatan() as $foto)
                        <li>{{ $foto->GetUrl() }}</li>
                        @endforeach
                    </ol>
                </td>
            </tr><tr>
                <td>Undangan</td>
                <td>:</td>
                <td>
                    <ol>
                        @foreach ($sppd->GetUndangan() as $foto)
                        <li>{{ $foto->GetUrl() }}</li>
                        @endforeach
                    </ol>
                </td>
            </tr>
        </table>
    </div>    
</body>
</html>