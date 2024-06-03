<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <table style="width: 85%">
        <tr>
            <td>
                <img src="https://www.unpak.ac.id/images/logo-unpak_menu_web.webp" alt="logo" style="width: 100px !important; height:100px !important;">
            </td>
            <td>
                <center>
                    <h3>
                        YAYASAN PAKUAN SILIWANGI<br>
                        UNIVERSITAS PAKUAN<br>
                    </h3>
                </center>
            </td>
        </tr>
    </table>
    <hr>
    <center>
        <h3>LAPORAN KLAIM ABSEN</h3>
    </center>
    <table border="1" align="center" width="100%" style="text-align: center">
        <tr style="background: black; color: white;">
            <td>#</td>
            <td>Nama</td>
            <td>Tanggal Absen</td>
            <td>Jam Masuk</td>
            <td>Jam Keluar</td>
            <td>Tujuan</td>
        </tr>
        @foreach($list_klaim_absen as $key=> $klaim_absen)
        @php
        $tgl_presensi = $klaim_absen->Presensi?->tanggal;
        $masuk_presensi = $klaim_absen->Presensi?->absen_masuk;
        $keluar_presensi = $klaim_absen->Presensi?->absen_keluar;
        $nama = match(true){
        !empty($klaim_absen->Dosen) && empty($klaim_absen->Pegawai) => $klaim_absen->Dosen?->nama_dosen."<br>".$klaim_absen->nidn,
        empty($klaim_absen->Dosen) && !empty($klaim_absen->Pegawai) => $klaim_absen->Pegawai?->nama."<br>".$klaim_absen->nip,
        default=>"NA"
        };
        @endphp
        <tr>
            <td>{{$key+1}}</td>
            <td>{!! $nama !!}</td>
            <td>{{empty($tgl_presensi)? 'NA':Carbon::parse($tgl_presensi)->setTimezone('Asia/Jakarta')->format("d F Y")}}</td>
            <td>
                <s>{{empty($masuk_presensi)? 'Tidak Masuk':Carbon::parse($masuk_presensi)->setTimezone('Asia/Jakarta')->format("H:i:s")}}</s>
                <br>
                {{$klaim_absen->jam_masuk}}
            </td>
            <td>
                <s>{{empty($keluar_presensi)? 'Tidak Keluar':Carbon::parse($keluar_presensi)->setTimezone('Asia/Jakarta')->format("H:i:s")}}</s>
                <br>
                {{$klaim_absen->jam_keluar}}
            </td>
            <td>{{$klaim_absen->tujuan}}</td>
        </tr>
        @endforeach
    </table>
</body>

</html>