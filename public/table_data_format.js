var format_pengguna = [
    {
        data: 'DT_RowIndex', 
        name: 'DT_RowIndex', 
        sWidth:'3%'
    },
    {
        data: 'nidn_username', 
        name: 'nidn_username',
        render: function ( data, type, row, meta ) {
            return data;
        }
    },
    {
        data: 'name', 
        name: 'name',
        render: function ( data, type, row, meta ) {
            return data;
        }
    },
    {
        data: 'level', 
        name: 'level',
        render: function ( data, type, row, meta ) {
            return data;
        }
    },
    {
        data: 'action', 
        name: 'action'
    },
]

var format_jenis_publikasi = [
    {
        data: 'DT_RowIndex', 
        name: 'DT_RowIndex', 
        sWidth:'3%'
    },
    {
        data: 'nama', 
        name: 'nama',
        render: function ( data, type, row, meta ) {
            return data;
        }
    },
    {
        data: 'sbu', 
        name: 'sbu',
        render: function ( data, type, row, meta ) {
            return data;
        }
    },
    {
        data: 'action', 
        name: 'action'
    },
]

var format_form_insentif = [
    {
        className: 'dt-control',
        orderable: false,
        data: null,
        defaultContent: '',
    },
    {
        data: 'DT_RowIndex', 
        name: 'DT_RowIndex', 
        sWidth:'3%'
    },
    {
        data: 'dosen', 
        name: 'nomor_rekening',
        render: function ( data, type, row, meta ) {
            return data?.e_pribadi?.payroll?.norek;
        }
    },
    {
        data: 'dosen', 
        name: 'nama_penulis',
        render: function ( data, type, row, meta ) {
            return data.nama_dosen;
        }
    },
    {
        data: 'dosen', 
        name: 'program_studi',
        render: function ( data, type, row, meta ) {
            return data.prodi?.nama_prodi;
        }
    },
    {
        data: 'dosen', 
        name: 'fakultas',
        render: function ( data, type, row, meta ) {
            return data.fakultas?.nama_fakultas;
        }
    },
    {
        data: 'judul_artikel', 
        name: 'judul_artikel',
        render: function ( data, type, row, meta ) {
            return data;
        }
    },

    {
        data: 'nama_jurnal_penerbit', 
        name: 'nama_jurnal_penerbit',
        render: function ( data, type, row, meta ) {
            return data;
        }
    },
    {
        data: 'JenisPublikasi', 
        name: 'jenis_publikasi',
        render: function ( data, type, row, meta ) {
            return data;
        }
    },
    {
        data: 'peran', 
        name: 'peran',
        render: function ( data, type, row, meta ) {
            return data;
        }
    },
    {
        data: 'link', 
        name: 'link',
        render: function ( data, type, row, meta ) {
            return data;
        }
    },
    {
        data: 'jumlah_penulis', 
        name: 'jumlah_penulis',
        render: function ( data, type, row, meta ) {
            return `${data} orang`;
        }
    },
    // {
    //     data: 'status_pengajuan', 
    //     name: 'status_pengajuan',
    //     render: function ( data, type, row, meta ) {
            // if(data==null) return `<span class='badge bg-warning text-black'>Menunggu</span>`;
            // else if(data=='1') return `<span class='badge bg-success'>Verif</span>`;
            // else return `<span class='badge bg-danger'>Tolak</span>`;
    //     }
    // },
    // {
    //     data: 'keterangan', 
    //     name: 'keterangan',
    //     render: function ( data, type, row, meta ) {
    //         return data;
    //     }
    // },
    // {
    //     data: 'verifikator', 
    //     name: 'verifikator',
    //     render: function ( data, type, row, meta ) {
    //         return data;
    //     }
    // },
    {
        data: 'action', 
        name: 'action'
    },
]
