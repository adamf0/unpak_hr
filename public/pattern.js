class FormProposalFactory {
    createShape(element,withClear=false,response,type) {
        switch (type) {
        case 'internal':
            return new FormPenelitianInternal(element,withClear,response);
        case 'pkm':
            return new FormPKM(element,withClear,response);
        default:
            throw new Error('Invalid type form proposal factory');
        }
    }
}
class Template {
    draw() {
        throw new Error('Method not implemented');
    }
}
class FormPenelitianInternal extends Template {
    constructor(element,withClear,response){
        super();
        this.element = element;
        this.withClear = withClear;
        this.response = response;
    }
    draw() {
        const responApi = this.response.data;
        const lengthStep = responApi.stepper.length        
        if(this.withClear) this.element.empty()

        let contentStepper = ``;
        responApi.stepper.forEach(function callback(value, index) {
            contentStepper += `<button class="nav-link circle-tab ${value.isActive? `active`:``}" id="step${index+1}-tab" data-bs-toggle="pill" data-bs-target="#step${index+1}" type="button" role="tab" aria-controls="step${index+1}" aria-selected="${value.isActive? 'true':'false'}" ${value.isDisable? `disabled`:``}>${value.numberStep}</button>`
            contentStepper += index==lengthStep-1? ``:`<div class="line"></div>`       
        })
        let contentAnggota = ``;
        responApi.listAnggota.forEach(function callback(value, index) {
            contentAnggota += `<tr>
                <td>${value.nidn??"-"}</td>
                <td>${value.nama??"-"} - ${value.prodi?.namaProdi??"-"} (${value.fakultas?.namaFakultas??"-"})</td>
            </tr>`       
        })
        let contentNonAnggota = ``;
        responApi.listNonAnggota.forEach(function callback(value, index) {
            contentNonAnggota += `<tr>
                <td>${value.npm??"-"}</td>
                <td>${value.nama??"-"}</td>
            </tr>`
        })
        let luaranTargetContent = ``;
        responApi.listLuaran.forEach(function callback(value, index) {
            luaranTargetContent += `<tr>
                <td>${index+1}</td>
                <td>${value.kategoriLaporan??"-"}</td>
                <td>${value.kategoriLuaran??"-"}</td>
            </tr>`
        })
        let rabContent = ``;
        responApi.listRab.forEach(function callback(value, index) {
            rabContent += `<tr>
                <td>${index+1}</td>
                <td>${value.kelompokRab??"-"}</td>
                <td>${value.komponen??"-"}</td>
                <td>${value.item??"-"}</td>
                <td>${value.satuan??"-"}</td>
                <td>${value.volume??"-"}</td>
            </tr>`
        })
        let rabDokumenPendukung = ``;
        responApi.listDokumenPendukung.forEach(function callback(value, index) {
            rabDokumenPendukung += `<tr>
                <td><a href="${value.fileMitra.url}">${value.fileMitra.fileName}</a></td>
                <td>${value.kategori??"-"}</td>
            </tr>`
        })

        let content = ` <div class="nav circle-tab-container mb-3" id="pills-tab" role="tablist">
                            ${contentStepper}
                                </div>

                                <div class="col-12">
                                    <div class="tab-content card" id="pills-tabContent">
                                        <div class="card-body tab-pane fade active show" id="step1" role="tabpanel" aria-labelledby="step1-tab">
                                                <div class="row">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <h5 class="text-primary">1.1 Identitas Usulan Penelitian</h5>
                                                        </div>
                                                        <div class="col-12">
                                                            ${viewInput("Pengusul",(responApi.dosen?.nidn??"-")+" "+(responApi.dosen?.nama??"-")+" - "+(responApi.dosen?.prodi?.namaProdi??"-")+" ("+(responApi.dosen?.fakultas?.namaFakultas??"-")+")")}
                                                        </div>
                                                        <div class="col-12">
                                                            ${viewInput("Judul",responApi.judul??"-")}
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                                ${viewInput("Kelompok Skema",responApi.skema??"-")}
                                                            </div>
                                                            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                                                ${viewInput("TKT",responApi.tkt??"-")}
                                                            </div>
                                                            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                                                ${viewInput("Kategori TKT",responApi.kategoriTkt??"-")}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-12">
                                                            <h5 class="text-primary">1.2 Pemilihan Program Penelitian</h5>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        ${viewInput("Bidang Fokus Penelitian",responApi.fokusPenelitian??"-")}
                                                                    </div>
                                                                    <div class="col-12">
                                                                        ${viewInput("Tema",responApi.tema??"-")}
                                                                    </div>
                                                                    <div class="col-12">
                                                                        ${viewInput("Topik",responApi.topik??"-")}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        ${viewInput("Rumpun Ilmu 1",responApi.rumpunIlmu1??"-")}
                                                                    </div>
                                                                    <div class="col-12">
                                                                        ${viewInput("Rumpun Ilmu 2",responApi.rumpunIlmu2??"-")}
                                                                    </div>
                                                                    <div class="col-12">
                                                                        ${viewInput("Rumpun Ilmu 3",responApi.rumpunIlmu3??"-")}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-6">
                                                                ${viewInput("Prioritas Riset",responApi.prioritasRiset??"-")}
                                                            </div>
                                                            <div class="col-6">
                                                                ${viewInput("Lama Kegiatan",responApi.lamaKegiatan??"-")}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-12">
                                                            <h5 class="text-primary">1.4 Identitas Pengusul - Anggota Peneliti</h5>
                                                        </div>
                                                        <div class="col-12">
                                                            <table id="tbAnggotaPeneliti" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>NIDN</th>
                                                                        <th>Nama</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    ${contentAnggota}  
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-12">
                                                            <h5 class="text-primary">1.5 Identitas Pengusul - Anggota Peneliti Non Dosen</h5>
                                                        </div>
                                                        <div class="col-12">
                                                            <table id="tbAnggotaPenelitiNonDosen" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>NPM</th>
                                                                        <th>Nama</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    ${contentNonAnggota}
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row d-flex justify-content-between">
                                                    <div class="col-2"></div>
                                                    <div class="col-2">
                                                        <button type="button" class="btn btn-primary" id="nextStep1">Lanjut</button>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="card-body tab-pane fade" id="step2" role="tabpanel" aria-labelledby="step2-tab">
                                                <div class="row">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <h5 class="text-primary">2.1 Substansi Usulan</h5>
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label">Unggah Substansi Laporan</label><br>
                                                            <a href="${responApi.substansiLuaran?.url??"#"}">${responApi.substansiLuaran?.fileName??"-"}</a>
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label">Unggah Template Proposal</label><br>
                                                            <a href="${responApi.proposalLuaran?.url??"#"}">${responApi.proposalLuaran?.fileName??"-"}</a>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-12">
                                                            <h5 class="text-primary">2.2 Luaran Target Capaian</h5>
                                                        </div>
                                                        <div class="col-12">
                                                            <table id="tbLuaran" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <td>#</td>
                                                                        <td>Kategori</td>
                                                                        <td>Luaran</td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="contentLuaran">
                                                                    ${luaranTargetContent}
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row d-flex justify-content-between">
                                                    <div class="col-2"></div>
                                                    <div class="col-2">
                                                        <button type="button" class="btn btn-secondary mr-2" id="prevStep2">Previous</button>
                                                        <button type="button" class="btn btn-primary" id="nextStep2">Lanjut</button>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="card-body tab-pane fade" id="step3" role="tabpanel" aria-labelledby="step3-tab">
                                                <div class="row">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <h5 class="text-primary">3.1 Rencana Anggaran Bekerja</h5>
                                                        </div>
                                                        <div class="col-12">
                                                            <table id="tbRab" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <td>#</td>
                                                                        <td>Kelompok RAB</td>
                                                                        <td>Komponen</td>
                                                                        <td>Item</td>
                                                                        <td>Satuan</td>
                                                                        <td>Action</td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="contentRab">
                                                                    ${rabContent}
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="row d-flex justify-content-between">
                                                        <div class="col-2"></div>
                                                        <div class="col-2">
                                                            <button type="button" class="btn btn-secondary mr-2" id="prevStep3">Previous</button>
                                                            <button type="button" class="btn btn-primary" id="nextStep3">Lanjut</button>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="card-body tab-pane fade" id="step4" role="tabpanel" aria-labelledby="step4-tab">
                                                <div class="row">
                                                    <div class="row mt-2">
                                                        <div class="col-12">
                                                            <h5 class="text-primary">4.1 Dokumen Pendukung (Jika Ada)</h5>
                                                        </div>
                                                        <div class="col-12">
                                                            <table id="tbDokumenPendukung" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Mitra</th>
                                                                        <th>Kategori</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    ${rabDokumenPendukung}
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="row d-flex justify-content-between">
                                                        <div class="col-2"></div>
                                                        <div class="col-2">
                                                            <button type="button" class="btn btn-secondary mr-2" id="prevStep4">Previous</button>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>`;

        this.element.append(content)

        const step1Tab = document.getElementById("step1-tab");
        const step2Tab = document.getElementById("step2-tab");
        const step3Tab = document.getElementById("step3-tab");
        const step4Tab = document.getElementById("step4-tab");

        $('#nextStep1').click(function(e) {
            e.preventDefault();
            step2Tab.removeAttribute("disabled");
            step2Tab.click();
        });
        $('#nextStep2').click(function(e) {
            e.preventDefault();
            step3Tab.removeAttribute("disabled");
            step3Tab.click();
        });
        $('#nextStep3').click(function(e) {
            e.preventDefault();
            step4Tab.removeAttribute("disabled");
            step4Tab.click();
        });

        $('#prevStep2').click(function(e){
            e.preventDefault();
            step1Tab.click();
        });
        $('#prevStep3').click(function(e) {
            e.preventDefault();
            step2Tab.click();
        });
        $('#prevStep4').click(function(e) {
            e.preventDefault();
            step3Tab.click();
        });
    }
}
class FormPKM extends Template {
    constructor(element,withClear,response){
        super();
        this.element = element;
        this.withClear = withClear;
        this.response = response;
    }
    draw() {
        const responApi = this.response.data;
        const lengthStep = responApi.stepper.length
        if(this.withClear) this.element.empty()

        let contentStepper = ``;
        responApi.stepper.forEach(function callback(value, index) {
            contentStepper += `<button class="nav-link circle-tab ${value.isActive? `active`:``}" id="step${index+1}-tab" data-bs-toggle="pill" data-bs-target="#step${index+1}" type="button" role="tab" aria-controls="step${index+1}" aria-selected="${value.isActive? 'true':'false'}" ${value.isDisable? `disabled`:``}>${value.numberStep}</button>`
            contentStepper += index==lengthStep-1? ``:`<div class="line"></div>`       
        })
        let contentAnggota = ``;
        responApi.listAnggota.forEach(function callback(value, index) {
            contentAnggota += `<tr>
                <td>${value.nidn??"-"}</td>
                <td>${value.nama??"-"} - ${value.prodi?.namaProdi??"-"} (${value.fakultas?.namaFakultas??"-"})</td>
            </tr>`       
        })
        let contentNonAnggota = ``;
        responApi.listNonAnggota.forEach(function callback(value, index) {
            contentNonAnggota += `<tr>
                <td>${value.npm??"-"}</td>
                <td>${value.nama??"-"}</td>
            </tr>`
        })
        let luaranContent = ``;
        responApi.listLuaran.forEach(function callback(value, index) {
            luaranContent += `<tr>
                <td>${index+1}</td>
                <td>${value.jenisLuaran??"-"}</td>
                <td>${value.indikatorCapaian??"-"}</td>
                <td>${value.status??"-"}</td>
            </tr>`
        })
        let rabContent = ``;
        responApi.listRab.forEach(function callback(value, index) {
            rabContent += `<tr>
                <td>${index+1}</td>
                <td>${value.kelompokRab??"-"}</td>
                <td>${value.komponen??"-"}</td>
                <td>${value.item??"-"}</td>
                <td>${value.satuan??"-"}</td>
                <td>${value.volume??"-"}</td>
            </tr>`
        })
        let dokumenMitraContent = ``;
        responApi.listDokumenMitra.forEach(function callback(value, index) {
            dokumenMitraContent += `<tr>
                <td>${value.mitra??"-"}</td>
                <td>${value.provinsi??"-"}</td>
                <td>${value.kota??"-"}</td>
                <td>${value.kelompokMitra??"-"}</td>
                <td>${value.pemimpinMitra??"-"}</td>
                <td><a href="${value.fileSuratPernyataan.url}">${value.fileSuratPernyataan.fileName}</a></td>
            </tr>`
        })

        let content = ` <div class="nav circle-tab-container mb-3" id="pills-tab" role="tablist">
                            ${contentStepper}
                        </div>

                        <div class="col-12">
                            <div class="tab-content card" id="pills-tabContent">
                                <div class="card-body tab-pane fade active show" id="step1" role="tabpanel" aria-labelledby="step1-tab">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-12">
                                                <h5 class="text-primary">1.1 Identitas Usulan Penelitian</h5>
                                            </div>
                                            <div class="col-12">
                                            ${viewInput("Pengusul",(responApi.dosen?.nidn??"-")+" "+(responApi.dosen?.nama??"-")+" - "+(responApi.dosen?.prodi?.namaProdi??"-")+" ("+(responApi.dosen?.fakultas?.namaFakultas??"-")+")")}
                                            </div>
                                            <div class="col-12">
                                                ${viewInput("Judul",responApi.judul??"-")}
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <h5 class="text-primary">1.2 Pemilihan Program Penelitian</h5>
                                            </div>
                                            <div class="row">
                                                <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                    ${viewInput("Kategori Program Pengabdian",responApi.kategoriProgramPengabdian??"-")}
                                                </div>
                                                <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                    ${viewInput("Fokus Pengabdian",responApi.fokusPengabdian??"-")}
                                                </div>
                                                <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                    ${viewInput("RIRN",responApi.rirn??"-")}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                                <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                    ${viewInput("Rumpun Ilmu 1",responApi.rumpunIlmu1??"-")}
                                                </div>
                                                <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                    ${viewInput("Rumpun Ilmu 2",responApi.rumpunIlmu2??"-")}
                                                </div>
                                                <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                    ${viewInput("Rumpun Ilmu 3",responApi.rumpunIlmu3??"-")}
                                                </div>
                                            </div>
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <h5 class="text-primary">1.4 Identitas Pengusul - Anggota Peneliti</h5>
                                            </div>
                                            <div class="col-12">
                                                <table id="tbAnggotaPeneliti" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>NIDN</th>
                                                            <th>Nama</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    ${contentAnggota}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <h5 class="text-primary">1.5 Identitas Pengusul - Anggota Peneliti Non Dosen</h5>
                                            </div>
                                            <div class="col-12">
                                                <table id="tbAnggotaPenelitiNonDosen" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>NPM</th>
                                                            <th>Nama</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        ${contentNonAnggota}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="row d-flex justify-content-between">
                                        <div class="col-2"></div>
                                        <div class="col-2">
                                            <button type="button" class="btn btn-primary" id="nextStep1">Lanjut</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body tab-pane fade" id="step2" role="tabpanel" aria-labelledby="step2-tab">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-12">
                                                <h5 class="text-primary">2.1 Substansi Usulan</h5>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Unggah Template Proposal</label><br>
                                                <a href="${responApi.substansiLuaran?.url??"#"}">${responApi.substansiLuaran?.fileName??"-"}</a>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Unggah Template Proposal</label><br>
                                                <a href="${responApi.proposalLuaran?.url??"#"}">${responApi.proposalLuaran?.fileName??"-"}</a>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <h5 class="text-primary">2.2 Luaran</h5>
                                            </div>
                                            <div class="col-12">
                                                <table id="tbLuaranMitra" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <td>#</td>
                                                            <td>Jenis Luaran</td>
                                                            <td>Indikator Capaian</td>
                                                            <td>Status</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="contentLuaranMitra">
                                                        ${luaranContent}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-flex justify-content-between">
                                        <div class="col-2">
                                            <input type="submit" name="submit" class="btn btn-secondary btnSubmitStep2" value="Simpan Draf" disabled>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" class="btn btn-secondary mr-2" id="prevStep2">Previous</button>
                                            <button type="button" class="btn btn-primary" id="nextStep2">Lanjut</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body tab-pane fade" id="step3" role="tabpanel" aria-labelledby="step3-tab">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-12">
                                                <h5 class="text-primary">3.1 Rencana Anggaran Bekerja</h5>
                                            </div>
                                            <div class="col-12">
                                                <table id="tbRab" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <td>#</td>
                                                            <td>Kelompok RAB</td>
                                                            <td>Komponen</td>
                                                            <td>Item</td>
                                                            <td>Satuan</td>
                                                            <td>Volume</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="contentRab">
                                                        ${rabContent}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-between">
                                            <div class="col-2"></div>
                                            <div class="col-2">
                                                <button type="button" class="btn btn-secondary mr-2" id="prevStep3">Previous</button>
                                                <button type="button" class="btn btn-primary" id="nextStep3">Lanjut</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body tab-pane fade" id="step4" role="tabpanel" aria-labelledby="step4-tab">
                                        <div class="row">
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <h5 class="text-primary">4.1 Mitra</h5>
                                                </div>
                                                <div class="col-12">
                                                    <table id="tbMitra" class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Mitra</th>
                                                                <th>Provinsi</th>
                                                                <th>Kota</th>
                                                                <th>Kelompok Mitra</th>
                                                                <th>Pemimpin Mitra</th>
                                                                <th>Surat Pernyataan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            ${dokumenMitraContent}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row d-flex justify-content-between">
                                                <div class="col-2"></div>
                                                <div class="col-2">
                                                    <button type="button" class="btn btn-secondary mr-2" id="prevStep4">Previous</button>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>`;

        this.element.append(content)

        const step1Tab = document.getElementById("step1-tab");
        const step2Tab = document.getElementById("step2-tab");
        const step3Tab = document.getElementById("step3-tab");
        const step4Tab = document.getElementById("step4-tab");

        $('#nextStep1').click(function(e) {
            e.preventDefault();
            step2Tab.removeAttribute("disabled");
            step2Tab.click();
        });
        $('#nextStep2').click(function(e) {
            e.preventDefault();
            step3Tab.removeAttribute("disabled");
            step3Tab.click();
        });
        $('#nextStep3').click(function(e) {
            e.preventDefault();
            step4Tab.removeAttribute("disabled");
            step4Tab.click();
        });

        $('#prevStep2').click(function(e){
            e.preventDefault();
            step1Tab.click();
        });
        $('#prevStep3').click(function(e) {
            e.preventDefault();
            step2Tab.click();
        });
        $('#prevStep4').click(function(e) {
            e.preventDefault();
            step3Tab.click();
        });
    }
}