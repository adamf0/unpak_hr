class SlipGajiFactory {
    createShape(element,withClear=false,response,type) {
        switch (type) {
        case 'dosen':
            return new SlipGajiDosen(element,withClear,response);
        case 'pegawai':
            return new SlipGajiPegawai(element,withClear,response);
        default:
            throw new Error('Invalid type form proposal factory');
        }
    }
}
class Template {
    draw() {
        throw new Error('Method not implemented');
    }
    formatRupiah(angka)
    {
        return angka.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ".");
    }
}
class SlipGajiDosen extends Template {
    constructor(element,withClear,response){
        super();
        this.element = element;
        this.withClear = withClear;
        this.response = response;
    }
    draw() {
        const responApi = this.response.data;
        if(this.withClear) this.element.empty()

        let astek_dlpk = responApi.gajikotor>0? (responApi.gajikotor-responApi.gajibersih):0;

        let mengajar= `
                <tr align="center">
                    <td align="left" style="vertical-align: top; padding-top: 0px">
                        &nbsp;<b>Mengajar :</b>
                    </td>
                    <td></td>
                    <td align="left" style="padding-top: 0px;">
                    </td>
                    <td colspan="3"></td>
                </tr>
                ${
                    responApi.mengajar>0? 
                    `
                    <tr align="center">
                        <td align="left" style="vertical-align: top; padding-top: 0px">
                            &nbsp;<b>-S1</b>
                        </td>
                        <td></td>
                        <td align="left" style="padding-top: 0px;">
                            Rp. 
                            <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.mengajar)}&nbsp;</label>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                    `
                    :``
                }
                ${
                    responApi.nonregular>0? 
                    `
                    <tr align="center">
                        <td align="left" style="vertical-align: top; padding-top: 0px">
                            &nbsp;<b>-S1-NonReg</b>
                        </td>
                        <td></td>
                        <td align="left" style="padding-top: 0px;">
                            Rp. 
                            <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.nonregular)}&nbsp;</label>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                    `
                    :``
                }
                ${
                    responApi.D3regular>0?
                    `
                    <tr align="center">
                        <td align="left" style="vertical-align: top; padding-top: 0px">
                            &nbsp;<b>-Vokasi</b>
                            </td>
                        <td></td>
                        <td align="left" style="padding-top: 0px;">
                            Rp. 
                            <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.D3regular)}&nbsp;</label>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                    `
                    :``
                }
                ${
                    responApi.D3nonregular>0?
                    `
                    <tr align="center">
                        <td align="left" style="vertical-align: top; padding-top: 0px">
                            &nbsp;<b>-Vokasi-NonReg</b>
                            </td>
                        <td></td>
                        <td align="left" style="padding-top: 0px;">
                            Rp. 
                            <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.D3nonregular)}&nbsp;</label>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                    `
                    :``
                }
                ${
                    responApi.pascasarjana>0? 
                    `
                    <tr align="center">
                        <td align="left" style="vertical-align: top; padding-top: 0px">
                            &nbsp;<b>-S2</b>
                            </td>
                        <td></td>
                        <td align="left" style="padding-top: 0px;">
                            Rp. 
                            <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.pascasarjana)}&nbsp;</label>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                    `
                    :``
                }
        `;
        let content = `
            <div class="col-12" style="ont-size: 12px;">
                <table width="355px" style="font-weight: bold; border-collapse: collapse; border: 1px solid; margin-bottom: 3px">
                    <tbody>
                        <tr align="center" style="">
                            <td style="padding-top: 5px">
                                <h4 style="font-weight: bold; margin: 0px" class="fs-6">UNIT KERJA/FAKULTAS REKTORAT</h4>
                                <h4 style="font-weight: bold; margin: 0px" class="fs-6">UNIVERSITAS PAKUAN</h4>
                                <h3 style="font-weight: bold; margin: 0px" class="fs-5"><u>GAJI dan TUNJANGAN</u></h3>
                                <h4 style="font-weight: bold; margin: 3px" class="fs-6">Bulan/Tahun : Januari/2024</h4>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table width="355px" style="border-collapse: collapse; border: 1px solid;font-size: 12px;" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr align="center">
                            <td align="left" style="width: 100px; vertical-align: top">
                                &nbsp;
                                <b>No. Urut</b>
                            </td>
                            <td align="left" colspan="5">
                                : ${responApi.no_mesin}
                                <label style="margin: 0; float: right;"> Hari&nbsp;</label>
                            </td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top">
                                &nbsp;<b>Nama</b>
                            </td>
                            <td align="left" colspan="5">
                                : ${responApi.nama}
                            </td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 10px; padding-bottom: 0px">
                                &nbsp;<b>Gaji Pokok</b>
                            </td>
                            <td width="20px"></td>
                            <td width="110px" align="left" style="padding-top: 10px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.gaji_pokok)}&nbsp;</label>
                            </td>
                            <td width="10px"></td>
                            <td width="110px" align="left" style="padding-top: 10px;"></td>
                            <td></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">
                                &nbsp;<b>Suami/istri</b>
                            </td>
                            <td></td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.tkeluarga)}&nbsp;
                            </label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">&nbsp;<b>Anak</b></td>
                            <td></td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.tanak)}&nbsp;</label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">&nbsp;<b>Pangan</b></td>
                            <td></td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.tpangan)}&nbsp;</label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">&nbsp;<b>Struktural</b></td>
                            <td></td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.tstruktural)}&nbsp;</label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">&nbsp;<b>Fungsional</b></td>
                            <td></td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.tfungsional)}&nbsp;</label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        ${mengajar}
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">
                                &nbsp;<b>Transpot</b>
                            </td>
                            <td></td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.transpot)}&nbsp;</label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">
                                &nbsp;<b>Khusus</b>
                            </td>
                            <td></td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.tkhusus)}&nbsp;</label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">
                                &nbsp;<b>Astek/DPLK</b>
                            </td>
                            <td></td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.astekY)}&nbsp;</label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">
                                &nbsp;<b>BPJS</b>
                            </td>
                            <td style="border-bottom: 1px solid"></td>
                            <td align="left" style="padding-top: 0px; border-bottom: 1px solid">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.bpjs)}&nbsp;</label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px" colspan="4">
                                &nbsp;<b>Jumlah Pendapatan</b>
                            </td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">
                                    ${this.formatRupiah(responApi.gajikotor)}
                                </label>
                            </td>
                            <td></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 10px">
                                &nbsp;<b>Astek/DPLK</b>
                            </td>
                            <td></td>
                            <td align="left" style="padding-top: 10px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">
                                    ${this.formatRupiah(astek_dlpk)}&nbsp;
                                </label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">
                                &nbsp;<b>Koperasi</b>
                            </td>
                            <td></td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.pkoperasi)}&nbsp;</label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">
                                &nbsp;<b>Yayasan</b>
                            </td>
                            <td></td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.pyayasan)}&nbsp;</label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">
                                &nbsp;<b>Zakat 2.5%</b>
                            </td>
                            <td style="border-bottom: 1px solid"></td>
                            <td align="left" style="padding-top: 0px; border-bottom: 1px solid">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.pzakat)}&nbsp;</label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px" colspan="4">
                                &nbsp;<b>Jumlah Potongan</b>
                            </td>
                            <td align="left" style="padding-top: 0px; border-bottom: 1px solid">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(astek_dlpk)}</label>
                            </td>
                            <td></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px" colspan="4">
                                &nbsp;<b>Pendapatan Bersih</b>
                            </td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.gajibersih)}</label>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <table width="355px" cellpadding="0" cellspacing="0" style="padding: 0px 30px; font-size: 12px;">
                    <tbody>
                        <tr align="center">
                            <td align="left" style="width:45%; vertical-align: top">&nbsp;<b></b></td>
                            <td align="center" style="vertical-align: top">&nbsp;<b>Bogor, ${responApi.bulan} ${responApi.tahun}</b></td>
                        </tr>
                        <tr align="center">
                            <td align="center" style="vertical-align: top">&nbsp;<b>Yang Menerima,</b></td>
                            <td align="center" style="vertical-align: top">&nbsp;<b>Yang Menyerahkan,</b></td>
                        </tr>
                        <tr align="center">
                            <td align="center" style="vertical-align: top"><br><br><b>(${responApi.nama})</b></td>
                            <td align="center" style="vertical-align: top"><b></b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;

        this.element.append(content)
    }
}

class SlipGajiPegawai extends Template {
    constructor(element,withClear,response){
        super();
        this.element = element;
        this.withClear = withClear;
        this.response = response;
    }
    draw() {
        const responApi = this.response.data;
        if(this.withClear) this.element.empty()

        let astek_dlpk = responApi.gajikotor>0? (responApi.gajikotor-responApi.gajibersih):0;

        let mengajar= ``;
        let content = `
            <div class="col-12" style="ont-size: 12px;">
                <table width="355px" style="font-weight: bold; border-collapse: collapse; border: 1px solid; margin-bottom: 3px">
                    <tbody>
                        <tr align="center" style="">
                            <td style="padding-top: 5px">
                                <h4 style="font-weight: bold; margin: 0px" class="fs-6">UNIT KERJA/FAKULTAS REKTORAT</h4>
                                <h4 style="font-weight: bold; margin: 0px" class="fs-6">UNIVERSITAS PAKUAN</h4>
                                <h3 style="font-weight: bold; margin: 0px" class="fs-5"><u>GAJI dan TUNJANGAN</u></h3>
                                <h4 style="font-weight: bold; margin: 3px" class="fs-6">Bulan/Tahun : Januari/2024</h4>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table width="355px" style="border-collapse: collapse; border: 1px solid;font-size: 12px;" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr align="center">
                            <td align="left" style="width: 100px; vertical-align: top">
                                &nbsp;
                                <b>No. Urut</b>
                            </td>
                            <td align="left" colspan="5">
                                : ${responApi.no_mesin}
                                <label style="margin: 0; float: right;"> Hari&nbsp;</label>
                            </td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top">
                                &nbsp;<b>Nama</b>
                            </td>
                            <td align="left" colspan="5">
                                : ${responApi.nama}
                            </td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 10px; padding-bottom: 0px">
                                &nbsp;<b>Gaji Pokok</b>
                            </td>
                            <td width="20px"></td>
                            <td width="110px" align="left" style="padding-top: 10px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.gaji_pokok)}&nbsp;</label>
                            </td>
                            <td width="10px"></td>
                            <td width="110px" align="left" style="padding-top: 10px;"></td>
                            <td></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">
                                &nbsp;<b>Suami/istri</b>
                            </td>
                            <td></td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.tkeluarga)}&nbsp;
                            </label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">&nbsp;<b>Anak</b></td>
                            <td></td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.tanak)}&nbsp;</label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">&nbsp;<b>Pangan</b></td>
                            <td></td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.tpangan)}&nbsp;</label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">&nbsp;<b>Struktural</b></td>
                            <td></td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.tstruktural)}&nbsp;</label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">&nbsp;<b>Fungsional</b></td>
                            <td></td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.tfungsional)}&nbsp;</label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        ${mengajar}
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">
                                &nbsp;<b>Transpot</b>
                            </td>
                            <td></td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.transpot)}&nbsp;</label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">
                                &nbsp;<b>Khusus</b>
                            </td>
                            <td></td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.tkhusus)}&nbsp;</label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">
                                &nbsp;<b>Astek/DPLK</b>
                            </td>
                            <td></td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.astekY)}&nbsp;</label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">
                                &nbsp;<b>BPJS</b>
                            </td>
                            <td style="border-bottom: 1px solid"></td>
                            <td align="left" style="padding-top: 0px; border-bottom: 1px solid">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.bpjs)}&nbsp;</label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px" colspan="4">
                                &nbsp;<b>Jumlah Pendapatan</b>
                            </td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">
                                    ${this.formatRupiah(responApi.gajikotor)}
                                </label>
                            </td>
                            <td></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 10px">
                                &nbsp;<b>Astek/DPLK</b>
                            </td>
                            <td></td>
                            <td align="left" style="padding-top: 10px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">
                                    ${this.formatRupiah(astek_dlpk)}&nbsp;
                                </label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">
                                &nbsp;<b>Koperasi</b>
                            </td>
                            <td></td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.pkoperasi)}&nbsp;</label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">
                                &nbsp;<b>Yayasan</b>
                            </td>
                            <td></td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.pyayasan)}&nbsp;</label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px">
                                &nbsp;<b>Zakat 2.5%</b>
                            </td>
                            <td style="border-bottom: 1px solid"></td>
                            <td align="left" style="padding-top: 0px; border-bottom: 1px solid">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.pzakat)}&nbsp;</label>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px" colspan="4">
                                &nbsp;<b>Jumlah Potongan</b>
                            </td>
                            <td align="left" style="padding-top: 0px; border-bottom: 1px solid">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(astek_dlpk)}</label>
                            </td>
                            <td></td>
                        </tr>
                        <tr align="center">
                            <td align="left" style="vertical-align: top; padding-top: 0px" colspan="4">
                                &nbsp;<b>Pendapatan Bersih</b>
                            </td>
                            <td align="left" style="padding-top: 0px;">
                                Rp. 
                                <label style="margin: 0; float: right; font-weight: normal;">${this.formatRupiah(responApi.gajibersih)}</label>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <table width="355px" cellpadding="0" cellspacing="0" style="padding: 0px 30px; font-size: 12px;">
                    <tbody>
                        <tr align="center">
                            <td align="left" style="width:45%; vertical-align: top">&nbsp;<b></b></td>
                            <td align="center" style="vertical-align: top">&nbsp;<b>Bogor, ${responApi.bulan} ${responApi.tahun}</b></td>
                        </tr>
                        <tr align="center">
                            <td align="center" style="vertical-align: top">&nbsp;<b>Yang Menerima,</b></td>
                            <td align="center" style="vertical-align: top">&nbsp;<b>Yang Menyerahkan,</b></td>
                        </tr>
                        <tr align="center">
                            <td align="center" style="vertical-align: top"><br><br><b>(${responApi.nama})</b></td>
                            <td align="center" style="vertical-align: top"><b></b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;

        this.element.append(content)
    }
}