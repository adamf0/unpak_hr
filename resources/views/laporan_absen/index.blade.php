@extends('template.index')

@section('page-title')
    <x-page-title title="Laporan Presensi">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Laporan Presensi</li>
            </ol>
        </nav>
    </x-page-title>
@stop

@section('content')
    <style>
        .column_min {
            min-width: 200px !important;
        }
    </style>
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-12">
                    {{ Utility::showNotif() }}
                </div>
                <div class="col-12">
                    <div class="card">
                        <form method="get" class="m-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-floating mb-2">
                                            <input type="date" name="tanggal_mulai" class="form-control" id="tgl_awal"
                                                placeholder="Tanggal Awal" value="{{ $start ?? '' }}">
                                            <label for="tgl_awal">Tanggal Awal</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="date" name="tanggal_akhir" class="form-control" id="tgl_akhir"
                                                placeholder="Tanggal Akhir" value="{{ $end ?? '' }}">
                                            <label for="tgl_akhir">Tanggal Akhir</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary">Filter</button>
                            </div>
                        </form>
                    </div>
                    @if ($start && $end)
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0 p-2">List Data Presensi</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="tb" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIP/NIDN</th>
                                                    <th>Nama</th>
                                                    <th class="select-filter-unit">Unit Kerja</th>
                                                    <th class="select-filter">Status</th>
                                                    <th>Total</th>
                                                    @php
                                                        $startdate = new DateTime($start);
                                                        $currentdate = new DateTime($start);
                                                        $enddate = new DateTime($end);
                                                    @endphp
                                                    @while ($currentdate <= $enddate)
                                                        @php
                                                            $minggu = $currentdate->format('w') == 0 ? 'bg-danger' : '';
                                                            echo '<th class="text-nowrap ' .
                                                                $minggu .
                                                                '">' .
                                                                $currentdate->format('d/m/Y') .
                                                                '</th>';
                                                            $currentdate->add(new DateInterval('P1D'));
                                                        @endphp
                                                    @endwhile
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @stop

    @push('scripts')
        <script type="text/javascript" src="{{ Utility::loadAsset('my.js') }}"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        @if ($start && $end)
            <script>
                $(document).ready(function() {
                    const listdata = JSON.parse('<?= json_encode($listpegawai, JSON_HEX_APOS) ?>');
                    const listklaim = JSON.parse('<?= json_encode($listklaim) ?>');

                    const listpresensi = JSON.parse('<?= json_encode($listpresensi) ?>');
                    const listcuti = JSON.parse('<?= json_encode($listcuti) ?>');
                    const listizin = JSON.parse('<?= json_encode($listizin) ?>');
                    const listsppd = JSON.parse('<?= json_encode($listsppd) ?>');

                    const startdate = '<?= $start ?>';
                    const enddate = '<?= $end ?>';

                    $('#tb').DataTable({
                        dom: 'Bfrtip',
                        buttons: [{
                                extend: 'excelHtml5',
                                text: 'Excel',
                                action: function(e, dt, node, config) {

                                    var $buttonUnit = $('.buttonSelectUnit').detach();
                                    var $button = $('.buttonSelect').detach();

                                    // Trigger the Excel export
                                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node,
                                        config);

                                    // Revert the visibility after export
                                    $('th.select-filter').append($button);
                                    $('th.select-filter-unit').append($buttonUnit);
                                    // setTimeout(function() {}, 100);
                                },
                            },
                            {
                                extend: 'pdfHtml5',
                                orientation: 'landscape',
                                pageSize: 'A4',
                                download: 'open',
                                action: function(e, dt, node, config) {

                                    var $buttonUnit = $('.buttonSelectUnit').detach();
                                    var $button = $('.buttonSelect').detach();

                                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node,
                                        config);

                                    $('th.select-filter').append($button);
                                    $('th.select-filter-unit').append($buttonUnit);
                                },
                                customize: function(doc) {
                                    doc.pageSize = {
                                        width: 3000,
                                        height: 700
                                    };
                                    var body = doc.content[1].table.body;

                                    const tHeader = doc.content[1].table.body[0];
                                    let warna = [];
                                    tHeader.forEach(function(item, index) {
                                        if (index > 6) {
                                            var dayCell = item;
                                            var dateText = dayCell.text;

                                            let [day, month, year] = dateText.split(
                                                '/');
                                            var date = new Date(year + '-' + month +
                                                '-' + day);
                                            if (date.getDay() === 0) {
                                                warna[index] = 'red';
                                            } else {
                                                warna[index] = '';
                                            }
                                        }
                                    });

                                    body.forEach(function(row, rowIndex) {
                                        if (rowIndex > 0) {
                                            for (let index = 0; index < row.length; index++) {
                                                var dayCell = row[index];
                                                dayCell.fillColor = warna[index] ? warna[
                                                        index] :
                                                    dayCell.fillColor;
                                            }
                                        }
                                    });
                                }
                            }
                        ],
                        columns: [{
                                title: 'No.',
                                render: function(data, type, row, meta) {
                                    return meta.row + 1;
                                }
                            },
                            {
                                title: 'NIP/NIDN',
                                data: 'nik'
                            },
                            {
                                title: 'Name',
                                data: 'nama'
                            },
                            {
                                title: 'Unit Kerja',
                                data: 'pengangkatan',
                                render: function(data) {
                                    return data?.unit_kerja ?? ''
                                }
                            },
                            {
                                title: 'Status',
                                data: 'status'
                            },
                            {
                                className: 'text-center',
                                render: function(data, type, row, meta) {
                                    let presensi = listpresensi.filter(item => item.nik == row.nik);

                                    let totalabsensi = [];
                                    presensi.map(function(item) {
                                        totalabsensi.push(item.tanggal);
                                    });

                                    let cuti = listcuti.filter(item => item.nik == row.nik);

                                    cuti.map(function(item) {
                                        let startDateCuti = new Date(item.tanggal_mulai);
                                        let endDateCuti = new Date(item.tanggal_akhir);
                                        while (startDateCuti <= endDateCuti) {
                                            let year = startDateCuti.getFullYear();
                                            let month = String(startDateCuti.getMonth() + 1)
                                                .padStart(2,
                                                    '0');
                                            let day = String(startDateCuti.getDate()).padStart(2,
                                                '0');
                                            let full = `${year}-${month}-${day}`;
                                            let dayOfWeek = startDateCuti.getDay();
                                            if (dayOfWeek !== 0) {
                                                totalabsensi.push(full);
                                            }
                                            startDateCuti.setDate(startDateCuti.getDate() + 1);
                                        }
                                    });

                                    let sppd = listsppd.filter(item => item.nik == row.nik);

                                    sppd.map(function(item) {
                                        let startDateSppd = new Date(item.sppd.tanggal_berangkat);
                                        let endDateSppd = new Date(item.sppd.tanggal_kembali);
                                        while (startDateSppd <= endDateSppd) {
                                            let year = startDateSppd.getFullYear();
                                            let month = String(startDateSppd.getMonth() + 1)
                                                .padStart(2,
                                                    '0');
                                            let day = String(startDateSppd.getDate()).padStart(2,
                                                '0');
                                            let full = `${year}-${month}-${day}`;
                                            let dayOfWeek = startDateSppd.getDay();
                                            if (dayOfWeek !== 0) {
                                                totalabsensi.push(full);
                                            }
                                            startDateSppd.setDate(startDateSppd.getDate() + 1);
                                        }
                                    });

                                    let uniqueDates = [...new Set(totalabsensi)];

                                    let waktu = uniqueDates.filter(item => startdate <= item && item <=
                                        enddate);

                                    return waktu.length;
                                }
                            },
                            @while ($startdate <= $enddate)
                                @php
                                    $minggu = $startdate->format('w') == 0 ? 'bg-danger' : '';
                                @endphp {
                                    // data: "{{ $startdate->format('d-m-Y') }}",
                                    className: 'text-center {{ $minggu }}',
                                    data: 'nik',
                                    render: function(data) {
                                        let currentdate = "{{ $startdate->format('Y-m-d') }}";
                                        let klaim = listklaim.find(item => item.nik == data && item.absen
                                            .tanggal == currentdate);
                                        let getPresensi = listpresensi.find(item => item.nik == data &&
                                            item.tanggal == currentdate);
                                        let getCuti = listcuti.find(item => item.nik == data &&
                                            item.tanggal_mulai <= currentdate && currentdate <= item
                                            .tanggal_akhir);
                                        let getSppd = listsppd.find(item => item.nik == data &&
                                            item.sppd.tanggal_berangkat <= currentdate && currentdate <=
                                            item.sppd
                                            .tanggal_kembali);
                                        let getIzin = listizin.find(item => item.nik == data &&
                                            item.tanggal_pengajuan == currentdate);

                                        let minggu = "{{ $startdate->format('w') }}";
                                        let absenMasukTime = '';
                                        let absenKeluarTime = '';
                                        let showTime = '';
                                        if (getPresensi) {
                                            if (klaim) {
                                                showTime = klaim.jam_masuk + ":00 - " + klaim.jam_keluar +
                                                    ':00';
                                            } else {
                                                absenMasukTime = getPresensi.absen_masuk ? new Date(
                                                    getPresensi.absen_masuk).toLocaleTimeString(
                                                    'us-US', {
                                                        hour: '2-digit',
                                                        minute: '2-digit',
                                                        second: '2-digit',
                                                        hour12: false
                                                    }) : '';
                                                absenKeluarTime = getPresensi.absen_keluar ? new Date(
                                                    getPresensi.absen_keluar).toLocaleTimeString(
                                                    'us-US', {
                                                        hour: '2-digit',
                                                        minute: '2-digit',
                                                        second: '2-digit',
                                                        hour12: false
                                                    }) : '';

                                                showTime = (minggu != 0) ? absenMasukTime + (
                                                    absenMasukTime ?
                                                    ' - ' : '') + absenKeluarTime : '';
                                            }
                                        } else if (getSppd) {
                                            showTime = (minggu != 0) ? 'SPPD' : '';
                                        } else if (getCuti) {
                                            showTime = (minggu != 0) ? 'Cuti' : '';
                                        } else if (getIzin) {
                                            showTime = (minggu != 0) ? 'Izin' : '';
                                        }

                                        return showTime;
                                    },
                                },
                                @php
                                    $startdate->add(new DateInterval('P1D'));
                                @endphp
                            @endwhile
                        ],

                        "columnDefs": [{
                            "orderable": false,
                            "targets": [3, 4]
                        }],
                        data: listdata,
                        initComplete: function() {
                            let api = this.api();

                            api.columns('.select-filter-unit')
                                .every(function() {
                                    let column = this;

                                    let select = document.createElement('select');
                                    select.classList.add('buttonSelectUnit');
                                    select.add(new Option('Pilih', ''));
                                    column.header().appendChild(select);

                                    select.addEventListener('change', function() {
                                        column
                                            .search(select.value, {
                                                exact: true
                                            })
                                            .draw();
                                    });
                                    let uniqueData = new Set();

                                    column
                                        .data()
                                        .each(function(d) {
                                            if (d && typeof d === 'object' && 'unit_kerja' in d) {
                                                uniqueData.add(d.unit_kerja); // Extract unit_kerja
                                            }
                                        });

                                    Array.from(uniqueData)
                                        .sort()
                                        .forEach(function(value) {
                                            select.add(new Option(value, value));
                                        });
                                });

                            api.columns('.select-filter')
                                .every(function() {
                                    let column = this;

                                    let select = document.createElement('select');
                                    select.classList.add('buttonSelect');
                                    select.add(new Option('Pilih', ''));
                                    column.header().appendChild(select);

                                    select.addEventListener('change', function() {
                                        column
                                            .search(select.value, {
                                                exact: true
                                            })
                                            .draw();
                                    });

                                    column
                                        .data()
                                        .unique()
                                        .each(function(d) {
                                            select.add(new Option(d));
                                        });
                                });
                        }
                    });
                });
            </script>
        @endif
    @endpush
