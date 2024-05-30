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
            <td>Nama</td>
            <td>Tanggal Cuti</td>
            <td>Lama Cuti</td>
            <td>Jenis Cuti</td>
            <td>Tujuan</td>
        </tr>
        @foreach($list_cuti as $key=> $cuti)
        @php
            $nama = match(true){
                !empty($cuti->Dosen) && empty($cuti->Pegawai) => $cuti->Dosen?->nama_dosen."<br>".$cuti->nidn,
                empty($cuti->Dosen) && !empty($cuti->Pegawai) => $cuti->Pegawai?->nama."<br>".$cuti->nip,
                default=>"NA"
            };
            $tanggal_mulai = empty($cuti->tanggal_mulai)? 'NA':Carbon::parse($cuti->tanggal_mulai)->setTimezone('Asia/Jakarta')->format("d F Y");
            $tanggal_akhir = empty($cuti->tanggal_akhir)? 'NA':Carbon::parse($cuti->tanggal_akhir)->setTimezone('Asia/Jakarta')->format("d F Y"); 
        @endphp
        <tr>
            <td>{{$key+1}}</td>
            <td>{!! $nama !!}</td>
            <td>
                @if ($tanggal_mulai==$tanggal_akhir)
                    {{$tanggal_mulai}}
                @else
                    {{$tanggal_mulai}} - {{$tanggal_akhir}}
                @endif
            </td>
            <td>{{$cuti->lama_cuti}} hari</td>
            <td>{{$cuti->JenisCuti?->nama}}</td>
            <td>{{$cuti->tujuan}}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>