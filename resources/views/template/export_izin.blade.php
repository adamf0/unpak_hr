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
            <td>Tanggal Izin</td>
            <td>Jenis Izin</td>
            <td>Tujuan</td>
            <td>Catatan</td>
        </tr>
        @foreach($list_izin as $key=> $izin)
        @php
            $nama = match(true){
                !empty($izin->Dosen) && empty($izin->Pegawai) => $izin->Dosen?->nama_dosen."<br>".$izin->nidn,
                empty($izin->Dosen) && !empty($izin->Pegawai) => $izin->Pegawai?->nama."<br>".$izin->nip,
                default=>"NA"
            };
            $tanggal_pengajuan = empty($izin->tanggal_pengajuan)? 'NA':Carbon::parse($izin->tanggal_pengajuan)->setTimezone('Asia/Jakarta')->format("d F Y")
        @endphp
        <tr>
            <td>{{$key+1}}</td>
            <td>{!! $nama !!}</td>
            <td>{{$tanggal_pengajuan}}</td>
            <td>{{$izin->JenisIzin?->nama}}</td>
            <td>{{$izin->tujuan}}</td>
            <td>{{$izin->catatan}}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>