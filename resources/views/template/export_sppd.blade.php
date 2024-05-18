<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <table border="1" align="center" width="100%" style="text-align: center">
        <tr style="background: black; color: white;">
            <td>#</td>
            <td>NIDN</td>
            <td>NIP</td>
            <td>Tanggal SPPD</td>
            <td>Jenis SPPD</td>
            <td>Tujuan</td>
            <td>Keterangan</td>
            <td>Anggota</td>
            <td>Status</td>
        </tr>
        @foreach($list_sppd as $key=> $sppd)
        <tr>
            <td>{{$key+1}}</td>
            <td>{{$sppd->nidn}}</td>
            <td>{{$sppd->nip}}</td>
            <td>{{Carbon::parse($sppd->tanggal_berangkat)->format("L F Y")}} - {{Carbon::parse($sppd->tanggal_kembali)->format("L F Y")}}</td>
            <td>{{$sppd->id_jenis_sppd}}</td>
            <td>{{$sppd->tujuan}}</td>
            <td>{{$sppd->keterangan}}</td>
            <td>belum implementasi</td>
            <td>{{$sppd->status}}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>