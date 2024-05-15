function to_select_data_dropdown(element,selected){
    element.val(selected).trigger("change");
}
function load_dropdown(element, local=null, url, selected=null, placeholder=false, parent=null){
    if(local!=null){
        if(placeholder){
            local.unshift({
                id: '',
                text: placeholder
            });
        }

        $(element).select2({
            theme: 'bootstrap-5',
            dropdownParent: parent,
            data: local??[]
        }).val(selected).trigger("change");
    } else{
        $.ajax({
            type: "GET",
            url: url,
            data: {},
            dataType: 'json',
            accepts: 'json',    
            success: function (r1) {
                // console.log(selected);
                if(placeholder){
                    r1.unshift({
                        id: '',
                        text: placeholder
                    });
                }
                // console.log(r1);
                $(element).select2({
                    theme: 'bootstrap-5',
                    dropdownParent: parent,
                    data: r1
                }).val(selected).trigger("change");
            }
        });
    }
}

function eTable(ajax, format, rowCallback=function( row, data ){}, drawCallback=function(settings){}, element=null){ 
    if(element==null) element = '#tb';

    return $(element).DataTable({
        ajax: ajax,
        serverSide: true,
        processing: true,
        responsive: true,
        columns: format,
        rowCallback: rowCallback,
        drawCallback: drawCallback,
        initComplete: function() {
            $("input[type='search']").wrap("<form>");
            $("input[type='search']").closest("form").attr("autocomplete","off");
        },
        paging: true,
        autoFill: true
    });
}
class Url{
    constructor(url="", method="get", headers=false, json=false, accepted=false) {
        this.url = url;
        this.method = method;
        this.headers = headers;
        this.json = json;
        this.accepted = accepted;
    }
}

const errorHandlers = {
    0: () => 'Tidak ada koneksi atau terjadi kesalahan jaringan.',
    400: () => 'Permintaan tidak valid.',
    401: () => 'Akses tidak diizinkan.',
    403: () => 'Akses ditolak.',
    404: () => 'Halaman tidak ditemukan.',
    500: () => 'Kesalahan server internal.',
    502: () => 'Gateway atau proxy bermasalah.',
    503: () => 'Layanan tidak tersedia.',
    504: () => 'Waktu koneksi habis.',
    timeout: () => 'Request timeout.',
    parsererror: () => 'Kesalahan parsing permintaan JSON.',
    abort: () => 'Permintaan Ajax dibatalkan.',
    default: (error) => 'Kesalahan tidak teridentifikasi: ' + error,
};

function handleAjaxError(xhr, status, error, displayConsole = true, url=null) {
    const errorHandler = errorHandlers[xhr.status] || errorHandlers[status] || errorHandlers.default;
    if(displayConsole) console.log(errorHandler(error));
    else return `${errorHandler(error)} pada url ${url}`;
}

function ERequest(r,formData = null,Isuccess,Ierror){
    if(!r instanceof Url){
        throw new Error("url is required");
    }

    let config = {
        url: r.url,
        method: r.method, // First change type to method here    
        processData: false, // Prevent jQuery from processing the data
        contentType: false, // Prevent jQuery from setting the content type
        data: formData??new FormData(),
        success: function(response) {
            Isuccess(response);
        },
        error: function(xhr, status, error) {
            handleAjaxError(xhr, status, error)
            Ierror(xhr, status, error);
        }
    };
    if(r.headers){
        config.headers = r.headers;
    }
    if(r.json){
        config.dataType = 'json';
    }
    if(r.accepted){
        config.contentType = r.accepted; //'application/json;charset=UTF-8'
    }
    // console.log(config);
    $.ajax(config);
}

function formatRupiah(angka, prefix){
	var number_string = angka.replace(/[^,\d]/g, '').toString(),
	split   		= number_string.split(','),
	sisa     		= split[0].length % 3,
	rupiah     		= split[0].substr(0, sisa),
	ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
	if(ribuan){
		separator = sisa ? '.' : '';
		rupiah += separator + ribuan.join('.');
	}
 
	rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}

function setOptions(element, options, clear=false) {
    if(clear){
        element.empty();
    }
    return element.select2({
        // placeholder: '--pilih--',
        data: options
    });
}

function isJson(val) {
    return val instanceof Array || val instanceof Object;
}

async function retryPromiseAll(promises, tag, retries = 3) {
    let errors = [];
    for (let i = 0; i < retries; i++) {
        console.log(`----tag-${tag} call-${i + 1}----`);
        const results = await Promise.all(promises.map(p => p.catch(e => e)));
        errors = results.filter(result => !(isJson(result)));
        
        if(errors.length == 0) return results;
    }
    // throw new Error(`tag-${tag} failded to call`);
    const throError = errors.join("<br>");
    throw Error(throError);
}

function disableButton(button) {
    button.prop('disabled', true);
}
function enableButton(button) {
    button.prop('disabled', false);
}

function viewInput(title, value){
    return `<label class="form-label">${title}</label>
            <input type="text" class="form-control" value="${value}" disabled>`;
}

function confirmDelete(url, messageDialog) {
    if (confirm(messageDialog)) {
        window.location.href = url;
    } else {
        return false;
    }
}
function getInputArrays(name,column){
    var inputElements = document.querySelectorAll(`input[name^="${name}["][name$="][${column}]"]`);
    return Array.from(inputElements).reduce(function(result, inputElement) {
        result.push(inputElement.value);
        return result;
    }, []);
}
function isDuplicatValueDynamicInput(name,column,targetValue){
    var duplicates = getInputArrays(name,column).filter(function (value) {
        return value === targetValue;
    });
    
    return duplicates.length > 0
}