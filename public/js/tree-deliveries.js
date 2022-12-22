function initializeTable() {
    $('#table').bootstrapTable({
        formatLoadingMessage: function() {
            return 'Cargando, por favor espere...';
        },
        formatNoMatches: function() {
            return 'No se encontraron registros.'
        },
        formatRecordsPerPage: function (pageNumber) {
            return `${pageNumber} registros por pagina`;
        },
        formatShowingRows: function (pageFrom, pageTo, totalRows) {
            return `Mostrando filas ${pageFrom} a ${pageTo} de ${totalRows}`;
        }
    });

    loadTreeDeliveries();
}

function loadTreeDeliveries() {
    $.ajax({
        url: '/public/admin/tree-deliveries',
        type: 'get',
        dataType: 'json',
        success: function(result) {
            console.log(result.data);
            $('#table').bootstrapTable('load', result.data);
        },
        error: function(err) {
            err_resp = err.responseJSON;

            Swal.fire({
                allowOutsideClick: true,
                title: 'Error al cargar.',
                text: err_resp.user_message,
                icon: 'error'
            });
        }
    });
}


function loadTreeDeliveriesForm() {
    window.id = window.location.pathname.split('/')[4];
    // alert(window.id)
    $.ajax({
        url: '/public/admin/tree-deliveries/' + window.id,
        // url: '/public/admin/tree-deliveries',
        type: 'get',
        dataType: 'json',
        success: function(result) {
            let select = document.getElementById('adoption_species');
            window.species = [ ];
            console.log(result.data.adoption_species)
            result.data.adoption_species.forEach(function(specie) {
                var opt = document.createElement('option');
                opt.value = specie.species_id;
                opt.innerHTML = specie.species_name;
                select.appendChild(opt);
                window.species.push(specie);
            })
            document.getElementById('code_event').value = result.data.code_event;
            loadTreeDelivery(result.data.buyers);
        },
        error: function(err) {
            err_resp = err.responseJSON;

            Swal.fire({
                allowOutsideClick: true,
                title: 'Error al cargar.',
                text: err_resp.user_message,
                icon: 'error'
            });
        }
    });
}

function loadAllSpecies(){
    $.ajax({
        url: '/public/admin/species/',
        type: 'get',
        dataType: 'json',
        success: function(result) {
            window.all_species = [ ];
            result.data.forEach(function(specie) {
                window.all_species.push(specie);
            })
        },
        error: function(err) {
            err_resp = err.responseJSON;

            Swal.fire({
                allowOutsideClick: true,
                title: 'Error al cargar.',
                text: err_resp.user_message,
                icon: 'error'
            });
        }
    });
}

function editColumnFormatter(value, row, index, field) {
    return [
        `<a href="/public/admin/tree-deliveries/form/${row.id}" class="btn btn-primary btn-edit"><i class="fa fa-eye"></i></a>`
        // '<button class="btn btn-warning btn-edit" ><i class="fa fa-pencil"></i> </button>'
    ].join('');
}

function editFormFormatter(value, row, index, field) {
    return [
        `<button class="btn btn-primary btn-edit"><i class="fa fa-pencil"></i></button>`
    ].join('');
}

function deleteColumnFormatter(value, row, index, field) {
    return [
        '<button class="btn btn-danger btn-delete"><i class="fa fa-trash"></i></button>'
    ].join('');
}

var deleteColumnEvent = {
    'click .btn-delete': function(e, value, row, index) {
        Swal.fire({
            allowOutsideClick: true,
            title: '¿Esta seguro?',
            text: `Se eliminará la entrega del árbol ${row.id}.`,
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true
        }).then(resp => {
            console.log("Borrar: ", row);
            if (resp.value) {
                $.ajax({
                    url: `/admin/tree-delivery/${row.id}`,
                    type: 'delete',
                    dataType: 'json',
                    success: function(result) {
                        loadTreeDelivery();

                        Swal.fire({
                            title: '',
                            text: 'Se eliminó la entrega de árbol correctamente.',
                            icon: 'success'
                        });
                    },
                    error: function(err) {
                        Swal.fire({
                            allowOutsideClick: true,
                            title: 'Error al eliminar.',
                            text: `${err.error.user_message}`,
                            icon: 'error'
                        });
                    }
                });
            }
        });
    }
}

var editRegisterEvent = {
    'click .btn-edit': function(e, value, row, index) {
        $.ajax({
            url: `/public/admin/tree-deliveries/${window.id}`,
            type: 'get',
            dataType: 'json',
            success: function(result) {
                let buyer = result.data.buyers.filter(function(beneficiario) {
                    if(beneficiario.id == row.id) { 
                        return beneficiario; 
                    }
                })[0];
                getTreeDelevery(buyer);
            },
            error: function(err) {
                Swal.fire({
                    allowOutsideClick: true,
                    title: 'Error al buscar información.',
                    text: `${err.error.user_message}`,
                    icon: 'error'
                });
            }
        });
    }
}

var deleteRegisterEvent = {
    'click .btn-delete': function(e, value, row, index) {
        Swal.fire({
            allowOutsideClick: true,
            title: '¿Esta seguro?',
            text: `Se eliminará la entrega del árbol de ${row.name}.`,
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true
        }).then(resp => {
            if (resp.value) {
                console.log("Borrar: ", `/public/admin/tree-deliveries/${window.id}.${row.id}`);
                $.ajax({
                    url: `/public/admin/tree-deliveries/${window.id}.${row.id}`,
                    type: 'get',
                    dataType: 'json',
                    success: function(result) {
                        console.log("Borrar: ", result);
                        loadTreeDelivery(result);

                        Swal.fire({
                            title: '',
                            text: 'Se eliminó la entrega de árbol correctamente.',
                            icon: 'success'
                        });
                    },
                    error: function(err) {
                        console.log('error: ', err);
                        Swal.fire({
                            allowOutsideClick: true,
                            title: 'Error al eliminar.',
                            text: `${err.responseJSON.user_message}`,
                            icon: 'error'
                        });
                    }
                });
            }
        });
    }
}

var editRegisterExcelEvent = {
    'click .btn-edit': function(e, value, row, index) {
        //if() {}
        console.log(row);
        getTreeDelevery(row);
    }
}

var deleteRegisterExcelEvent = {
    'click .btn-delete': function(e, value, row, index) {
        Swal.fire({
            allowOutsideClick: true,
            title: '¿Esta seguro?',
            text: `Se eliminará la entrega del árbol de ${row.name}.`,
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true
        }).then(resp => {
            if (resp.value) {
                // remover la fila seleccionada
                window.excel_update = [];
                let pos = -1;
            
                window.excel.forEach(function(r){
                    console.log(row.id, r.id);
                    if(row.id != r.id) {
                        r.id = pos--;
                        window.excel_update.push(r);
                    }
                });
                window.excel = window.excel_update;
                $('#table-2').bootstrapTable('load', window.excel);
                console.log(window.excel);
            }
        });
        
    }
}



function fillTreeDeliveryForm(data) {
    var date = new Date(data.leaving_date);
    var day = ("0" + date.getDate()).slice(-2);
    var month = ("0" + (date.getMonth() + 1)).slice(-2);
    var dateFormat = date.getFullYear() + "-" + (month) + "-" + (day);

    $('#id').val(data.id);
    $('#event_id').val(data.event_id);
    $('#nursery_id').val(data.nursery_id);
    $('#species_id').val(data.species_id);
    $('#amount').val(data.amount);
    $('#labels').val(data.labels);
    $('#technical_user_id').val(data.technical_user_id);
    $('#leaving_date').val(dateFormat);
}

function cleanTreeDeliveryForm() {
    $('#id').val('');
    $('#name').val('');
    $('#phone').val('');
    $('#email').val('');
    $('#address').val('');
    $('#suburb').val('');
    $('#postal_code').val('');
    $('#adoption_species').val('');
    $('#longitude').val('');
    $('#latitude').val('');
}

function getTreeDelevery(data) {
    var address = getTreeDeleveryAdress(data.address);
    $('#id').val(data.id);
    $('#name').val(data.name);
    $('#phone').val(data.phone);
    $('#email').val(data.mail);
    $('#road_type').val(address[0]);
    $('#address').val(address[1]);
    $('#suburb').val(data.suburb);
    $('#postal_code').val(data.cp);
    $('#adoption_species').val(data.id_specie);
    $('#longitude').val(data.length);
    $('#latitude').val(data.latitude);
    //animar movimiento hacia arriba
    $('html, body').animate({ scrollTop: 0 }, 1200);
}
function getTreeDeleveryAdress(address){
    var roadType = '';
    var roadName = '';
    switch(address.split(" ")[0].toUpperCase()){
        case 'PRIVADA':
        case 'PRIV.':
        case 'PRIV':
            roadType = "Privada";
            break;
        case 'AVENIDA':
        case 'AV.':
        case 'AV':
            roadType = "Avenida";
            break;
        case 'CARRETERA':
            roadType = "Carretera";
            break;
        case 'CALLE':
            roadType = "Calle";
            break;
        default:
            roadType = "Calle";
            address = "Calle " + address;
            break;
    }
    roadName = address.slice(address.split(" ")[0].length+1)
    return [roadType, roadName];
}

function saveTreeDelevery() {
    // verificar si es nuevo, viene de la base de datos o viene del excel
    // nuevo -> id = null
    // bd    -> id = positivo
    // excel -> id = negativo

    var error = false;
    var id_adoption = window.id;
    var name = $('#name').val();
    var phone = $('#phone').val();
    var mail = $('#email').val();
    var address = $('#address').val();
    var suburb = $('#suburb').val();
    var cp = $('#postal_code').val();
    var id_specie = $('#adoption_species').val();
    var longitude = $('#longitude').val();
    var latitude = $('#latitude').val();

    !name && (error = true) && toastr.warning('El nombre es requerido.', 'Información incompleta!');
    !phone && (error = true) && toastr.warning('El telefono es requerido.', 'Información incompleta!');
    //!mail && (error = true) && toastr.warning('El correo es requerido.', 'Información incompleta!');
    !address && (error = true) && toastr.warning('La direccion es requerida.', 'Información incompleta!');
    //!suburb && (error = true) && toastr.warning('La colonia es requerida.', 'Información incompleta!');
    //!cp && (error = true) && toastr.warning('El codigo postal es requerido.', 'Información incompleta!');
    //cp.length!=5 && (error = true) && toastr.warning('El codigo postal no es valido.', 'Información invalida!');
    id_specie == -1 && (error = true) && toastr.warning('Debe seleccionar una especie.', 'Información incompleta!');
    //!longitude && (error = true) && toastr.warning('La longitud no se ha encontrado.', 'Información incompleta!');
    //!latitude && (error = true) && toastr.warning('La latitud no se ha encontrado.', 'Información incompleta!');

    if(error) { return; }

    var data = {
        'id_adoption': id_adoption,
        'name': name,
        'phone': phone,
        'mail': mail,
        'address': address,
        'suburb': suburb,
        'cp': cp,
        'id_specie': Number(id_specie),
        'latitude': latitude,
        'length': longitude
    }

    // console.log(id_adoption);
    let id = $('#id').val();
    if(isNumber(id) && id >= 0) {
        console.log("base de datos: ");
        sendUpsertTreeDeliveryRequest(`/public/admin/tree-deliveries/${id_adoption}`, 'put', data);
    } else if(isNumber(id) && id < 0) {
        //modificar el excel
        data.id = Number(id);
        data.species_name = $('#adoption_species option:selected').text();
        window.excel[-(Number(id)+1)] = data;

        $('#table-2').bootstrapTable('load', window.excel);
        cleanTreeDeliveryForm();
    } else {
        console.log("nuevo: ");
        sendUpsertTreeDeliveryRequest(`/public/admin/tree-deliveries/${id_adoption}`, 'put', data);
    }
}

function sendUpsertTreeDeliveryRequest(url, type, data, isList=false) {
    $.ajax({
        url: url,
        type: type,
        data: data,
        dataType: 'json',
        success: function(result) {
            cleanTreeDeliveryForm();
            $("#addModal").modal('hide');
            loadTreeDeliveriesForm();
            loadAllSpecies();
            // console.log(result.data);
            if(!isList) {
                Swal.fire({
                    title: result.data.id,
                    text: 'Datos guardados correctamente.',
                    icon: 'success',
                });
            }
        },
        error: function(err) {
            console.log('error: ', err);
            err_resp = err.responseJSON;

            Swal.fire({
                allowOutsideClick: true,
                title: 'Error al guardar.',
                text: err_resp.user_message,
                icon: 'error'
            });
        }
    });
}

function loadTreeDelivery(buyers) {
    //cargar el table-2
    // console.log(buyers);
    $('#table-3').bootstrapTable('load', buyers);
}

/** Configurar Mapa */
function settingUpMap() {
    // Coordenadas iniciales; en Guadalajara
    var init_coordinates = [20.66682, -103.39182];
    myMap.setView(init_coordinates, 13);

	L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        // id: 'mapbox/satellite-v9',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1IjoiaGFydGVjLWRldmVsb3BlciIsImEiOiJja3JmOXhmeHgwbGRyMm9vMjNwc3Y0YzU3In0.jpb4jHIrYnm1zVGq36OsBw'
    }).addTo(myMap);

    myMap.on('click', function(e) {
        document.getElementById('latitude').value = e.latlng.lat;
        document.getElementById('longitude').value = e.latlng.lng;
        
        myMapPopup.setLatLng(e.latlng)
            .setContent('Latitud: ' + e.latlng.lat + ',<br>Longitud: ' + e.latlng.lng)
            .openOn(myMap);

        myMapCircleMarker.setLatLng(e.latlng).addTo(myMap);
    });
}

/**  Geocodificador */
function getGeocode() {
    var api_key = 'b2a1a5d97eb24800b3c15a4cfed1431b';
    var road_type = document.getElementById('road_type').value;
    var address = document.getElementById('address').value;
    var suburb = document.getElementById('suburb').value;
    var postal_code = document.getElementById('postal_code').value;
    //https://opencagedata.com/guides/how-to-format-your-geocoding-query
    var search_text = (road_type ? (road_type + ' ') : '')
        + (address ? (address + ', ') : '')
        + (suburb ? (suburb + ', ') : '')
        //+ (postal_code ? (postal_code + ', ') : '')
        + 'México';
    var search_query = encodeURIComponent(search_text);

    var api_url = 'https://api.opencagedata.com/geocode/v1/json?q=';

    var request_url = api_url
        + search_query
        + '&countrycode=mx'
        // + '&language=es'
        // + '&limit=4'
        + '&pretty=1'
        + '&proximity=20.66682,-103.39182'
        + '&key=' + api_key;

    // see full list of required and optional parameters:
    // https://opencagedata.com/api#forward

    var request = new XMLHttpRequest();
    request.open('GET', request_url, true);

    request.onload = function() {
        // see full list of possible response codes:
        // https://opencagedata.com/api#codes

        if (request.status === 200){ 
            // Success!
            var data = JSON.parse(request.responseText);
            var lat = data.results[0].geometry.lat; //Latitud de la busqueda
            var lng = data.results[0].geometry.lng; //Longitus de la busqueda

            //Mueve mapa a la ubicación encontrada
            myMap.flyTo([lat, lng], 18);
            myMapCircleMarker.setLatLng([lat, lng]).addTo(myMap);

            //escribe latitud y longitud en text_input
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        } else if (request.status <= 500){ 
            // We reached our target server, but it returned an error            
            console.log("unable to geocode! Response code: " + request.status);
            var data = JSON.parse(request.responseText);
            console.log('error msg: ' + data.status.message);
        } else {
            console.log("server error");
        }
    };

    request.onerror = function() {
        // There was a connection error of some sort
        console.log("unable to connect to server");        
    };

    request.send();  // make the request
}

/** Subir Excel */
function uploadExcel(event) {
    $('#load-xls').show();
    console.log("Subida de archivo")
    selectedFile = event.target.files[0];
    // alert("Se esta subiendo un archivo")
    readExcel();
}
function readExcel() {
    if(selectedFile) {
        var fileReader = new FileReader();

        fileReader.onload = function(event) {
            var data = event.target.result;
            var workbook = XLSX.read(data, {
                type: 'binary'
            });

            workbook.SheetNames.forEach(sheet => {
                let rowObject = XLSX.utils.sheet_to_row_object_array(
                    workbook.Sheets[sheet]
                );
                // console.log(rowObject)
                //let jsonObject = JSON.stringify(rowObject);
                if(sheet!=='Validaciones'){
                    writeExcel(rowObject);
                    }
                // console.log(workbook.Sheets[sheet]);
                // console.log(workbook.Sheets);
                // console.log(sheet);
                // console.log("__________________________________");
            });
        };
        fileReader.readAsBinaryString(selectedFile);
    }
}

function writeExcel(jsonObject) {
    console.log(jsonObject)
    console.log(window.all_species)
    //$('#table-2').bootstrapTable("destroy");
    let id = -1;
    for(var i = 0; i < jsonObject.length; i++) {
        jsonObject[i].name = jsonObject[i].Nombre;
        delete jsonObject[i].Nombre;
        jsonObject[i].mail = jsonObject[i].Email;
        delete jsonObject[i].Email;
        jsonObject[i].latitude = jsonObject[i].Latitud;
        delete jsonObject[i].Latitud;
        jsonObject[i].length = jsonObject[i].Longitud;
        delete jsonObject[i].Longitud;
        jsonObject[i].vialidad = jsonObject[i].Vialidad;
        delete jsonObject[i].Vialidad;
        jsonObject[i].address = jsonObject[i].Direccion;
        delete jsonObject[i].Direccion;
        if(jsonObject[i].vialidad){
            jsonObject[i].address = jsonObject[i].vialidad + " " + jsonObject[i].address;
        }
        delete jsonObject[i].vialidad
        jsonObject[i].suburb = jsonObject[i].Colonia;
        delete jsonObject[i].Colonia;
        jsonObject[i].phone = jsonObject[i].Telefono;
        delete jsonObject[i].Telefono;
        jsonObject[i].cp = jsonObject[i].CodigoPostal;
        delete jsonObject[i].CodigoPostal;

        let specie = jsonObject[i].Especie;
        delete jsonObject[i].Especie;
        let valid = false;

        //buscar si el nombre de la especie se encuentra en la lista
        // window.species.forEach(function (especie) {
            // console.log(especie.species_name)
            // if(especie.species_name.toUpperCase() == specie.toUpperCase()) {
                // valid = true;
                // jsonObject[i].id_specie = especie.species_id;
            // }
        // });
        window.all_species.forEach(function (especie) {
            console.log('Especie e la BD: ',especie.name.toUpperCase())
            console.log('Especie del excel: ',specie.toUpperCase())
            if(especie.name.toUpperCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").trim() == specie.toUpperCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").trim()) {
                valid = true;
                console.log(especie.name)
                jsonObject[i].id_specie = especie.id;
            }
        });

        jsonObject[i].id_adoption = window.id;

        if(valid) {
            jsonObject[i].species_name = specie;
        } else {
            jsonObject[i].species_name = `<span style="color:red;">${specie}</span>`;
            jsonObject[i].error = true;
        }

        jsonObject[i].id = id--;
    }
    window.excel = jsonObject;

    document.getElementById('load-xls').removeAttribute("hidden");
    $('#table-2').bootstrapTable('load', window.excel);
}


function deleteExcel(){
    // alert("Se ocultara la tabla");
    $('#load-xls').hide();
    window.excel_update = [];
    let pos = -1;
    window.excel.forEach(function(r){
        console.log(row.id);
        if(row.id != r.id) {
            r.id = pos--;
            window.excel_update.push(r);
        }
    });
    window.excel = window.excel_update;
    $('#table-2').bootstrapTable('load', window.excel);
    console.log(window.excel);
}
function saveExcel() {
    //document.getElementById('load-xls').setAttribute("hidden", true);
    $('#table-2').empty();
    window.excel_error = [];
    let pos = -1;

    window.excel.forEach(function(row){
        if(!row.error) {
            delete row.id;
            sendUpsertTreeDeliveryRequest(`/public/admin/tree-deliveries/${window.id}`, 'put', row, true);
        } else {
            row.id = pos--;
            window.excel_error.push(row);
        }
    });
    
    Swal.fire({
        title: "Genial",
        text: 'Datos guardados correctamente.',
        icon: 'success',
    });


    // guardar los datos con errores para corregirlos
    window.excel = window.excel_error;
    $('#table-2').bootstrapTable('load', window.excel);
}

function isNumber(n){
    return !isNaN(parseInt(n));
}