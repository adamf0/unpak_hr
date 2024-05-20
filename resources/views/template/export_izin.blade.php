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
        <tr>
            <td>{{$key+1}}</td>
            <td>
                @php
                    $nama = match(true){
                        !empty($izin->nama_dosen) && empty($izin->nama_pegawai) => $izin->nama_dosen."<br>".$izin->nidn,
                        empty($izin->nama_dosen) && !empty($izin->nama_pegawai) => $izin->nama_pegawai."<br>".$izin->nip
                    };
                    echo $nama;
                @endphp
            </td>
            <td>{{Carbon::parse($izin->tanggal_pengajuan)->format("L F Y")}}</td>
            <td>{{$izin->nama_izin}}</td>
            <td>{{$izin->tujuan}}</td>
            <td>{{$izin->catatan}}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>