$(document).ready(function () {

    //configuracion del datatables - poner tal cual
    $('#table-abm').DataTable({
        "drawCallback":function(settings){
            $('#table-abm').wrap("<div class='table-responsive'></div>");
        },
        "language":espanol,
        "order":[[2,'asc']],
        "autoWidth":false
    });

    $('#table-abm').css('width','100%');

    //CARGAR TABLA
    loadAbmTable();

    // Creating map object
    var map = new google.maps.Map(document.getElementById('map_canvas'), {
        zoom: 12,
        center: new google.maps.LatLng(-34.9206797, -57.9537638),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    // creates a draggable marker to the given coords
    var vMarker = new google.maps.Marker({
        position: new google.maps.LatLng(-34.9206797, -57.9537638),
        draggable: true
    });

    // adds a listener to the marker
    // gets the coords when drag event ends
    // then updates the input with the new coords
    google.maps.event.addListener(vMarker, 'dragend', function (evt) {
        $("#txtLat").val(evt.latLng.lat().toFixed(6));
        $("#txtLng").val(evt.latLng.lng().toFixed(6));

        map.panTo(evt.latLng);
    });

    // centers the map on markers coords
    map.setCenter(vMarker.position);

    // adds the marker on the map
    vMarker.setMap(map);
});

/***********************************************************************
 *  CARGAR TABLA
 ************************************************************************/

function loadAbmTable(){
    var url = $('meta[name="uri"]').attr('content'); // llama a la url global que se encuantra en el head -> meta

    /*************************************************************************************************************************
     / poner tal cual y completar las columns deben coincidir con las columns de la vista th -> dejar la primer columns en null
     / la peticion debe retornar un array data[] con la cual se contruye la tabla
     **************************************************************************************************************************/

    var t = $('#table-abm').DataTable( {
        "destroy":true,                                     // destruye la tabla para crearla de cero siempre
        "ajax": url,
        "columns":[
            {"data":null},
            {"data":"name"},
            {"data":"lat"},
            {"data":"lng"},
            {sortable: false, // poner tal cual -> con la funcion reder creo lo botones de acciones y retorno un html
                "render": function (data, type, row, meta) {
                    return "<div class='btn-group'>" +
                        "<button style='margin-right: 4px' class='btn btn-sm btn-primary'  title='Editar' value='"+row.id+"'  onclick='getModalEdit(this)'><i class='fa fa-pencil' aria-hidden='true'></i></button>" +
                        "<button style='margin-right: 4px' class='btn btn-sm btn-primary'  title='Eliminar' value='"+row.id+"'  onclick='deleteDataRow(this)'><i class='fa fa-trash' aria-hidden='true'></i></a>" +
                        "</div>";
                }
            },
        ],
        "drawCallback":function(settings){
            $('#table-abm').wrap("<div class='table-responsive'></div>"); // tabla responsiva poner tal cual
        },
        "language":espanol,
        "autoWidth":false,
        "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ]
    } );

    t.on( 'order.dt search.dt', function () {  //agrega una columna al principio para la numeracion --> por eso porner la primer column en null
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    $('#table-abm').css('visibility','visible');   // pongo visible la tabla para que se vea completa cuando esta cargada
}


$('#btn-ubications').on('click', function(){
    $('#tabla').show(300);
});

$('.btn-close').on('click', function(){
    $('#tabla').hide(300);
});

/***********************************************************************
 *  CREAR
 ************************************************************************/

$('#form-create').submit(function(e){
    e.preventDefault();
    var url = $('meta[name="uri"]').attr('content'); // llama a la url global que se encuantra en el head -> meta
    var token = $('#token-create').val();
    $('#btn-create').css('display','none');                  //oculto boton guardar
    $('#btn-preload-create').css('display','inline-block');  //mostrar preload
    var data = $('#form-create').serialize();  //Guardo la data del form
    ClearFormError(); //metodo para limpiar el formulario de error

    $.ajax({
        url: url,
        headers:{'X-CSRF-TOKEN':token},
        type: 'POST',
        dataType: 'json',
        data: data,
        success: function(data){
            toastr["success"]("Data creada con Exito");  // lanza notificaciones - success en este caso
            $('#btn-create').css('display','inline-block');
            $('#btn-preload-create').css('display','none');
            window.scrollTo(0,0);
            ClearForm();                                               // borrar datos formulario
            ClearFormError();                                          // borrar alerts error en formulario
            loadAbmTable();
        },
        error: function (error) {
            $.each( error.responseJSON.errors, function( key, value ) {
                $('#error-' + key).text(value);
            });

            toastr["error"]("Completá o corregí los datos indicados"); // lanza notificaciones - error en este caso
            $('#btn-create').css('display','inline-block');   // habilitar boton guardar
            $('#btn-preload-create').css('display','none');   // ocultar preload
        }
    });
    setTimeout(function() {
        $('#btn-create').css('display','inline-block');
        $('#btn-preload-create').css('display','none');
    },2000);

    return false;
});

function ClearForm(){
    $('#name').val('');
    $('#txtLat').val('');
    $('#txtLng').val('');
}

function ClearFormError(){
    $('#error-name').text('');
    $('#error-lat').text('');
    $('#error-lng').text('');
}

/***********************************************************************
 *  EDITAR
 ************************************************************************/

//abrir modal editar y visualizar los datos para editar
function getModalEdit(btn){

    $("#form-edit")[0].reset(); // borrar formulario
    $('#modal-edit').modal('show');  //abrir modal
    var uri = $('meta[name="uri"]').attr('content'); // llama a la url global que se encuantra en el head -> meta
    var url = uri + '/' + btn.value + '/edit'; // concateno la url global
    var token = $('#token-edit').val();

    $.ajax({
        url: url,
        type: 'GET',
        cache:false,
        success:function(res){
            if(res){
                $(res).each(function(key,value){
                    $('#id-edit').val(value.id);
                    $('#name-edit').val(value.name);
                    $('#lat-edit').val(value.lat);
                    $('#lng-edit').val(value.lng);
                });
                $('html,body').scrollTop(0);
            }else{
                toastr["error"]("Error al traer datos");
            }
        }
    });
}

//Editar   //logica igual que el create solo cambia en el back
$('#form-edit').submit(function(e){
    e.preventDefault();
    var uri = $('meta[name="uri"]').attr('content'); // llama a la url global que se encuantra en el head -> meta
    var token = $('#token-edit').val();
    $('#btn-edit').css('display','none');
    $id = $('#id-edit').val();
    $('#btn-preload-edit').css('display','inline-block');
    var url = uri + '/' + $id; // concateno la url global
    var data = $('#form-edit').serialize();
    ClearFormErrorEdit();

    $.ajax({
        url: url,
        headers:{'X-CSRF-TOKEN':token},
        type: 'POST',
        dataType: 'json',
        data:data,
        success:function(data){
            toastr["success"]("Datos Actualizados con Exito");
            $('#btn-edit').css('display','inline-block');
            $('#btn-preload-edit').css('display','none');
            window.scrollTo(0,0);
            ClearFormEdit();
            ClearFormErrorEdit();
            $('#modal-edit').modal('hide');
            loadAbmTable();
        },
        error: function (error) {

            $.each( error.responseJSON.errors, function( key, value ) {
                console.log(key)
                $('#error-'+key+'-edit').text(value);
            });

            toastr["error"]("Completá o corregí los datos indicados"); // lanza notificaciones - error en este caso
            $('#btn-create').css('display','inline-block');   // habilitar boton guardar
            $('#btn-preload-create').css('display','none');   // ocultar preload
        }
    });
    setTimeout(function() {
        $('#btn-edit').css('display','inline-block');
        $('#btn-preload-edit').css('display','none');
    },2000);
    return false;
});

$('#btn-close-edit').on('click',function(){
    ClearFormEdit();
    ClearFormErrorEdit();
});

$('.close-edit').on('click',function(){
    ClearFormEdit();
    ClearFormErrorEdit();
});

function ClearFormEdit(){
    $('#name-edit').val('');
    $('#lat-edit').val('');
    $('#lng-edit').val('');
}

function ClearFormErrorEdit(){
    $('#error-name-edit').text('');
    $('#error-lat').text('');
    $('#error-lng-edit').text('');
}

/***********************************************************************
 *  BORRAR
 ************************************************************************/

function deleteDataRow(btn){
    var uri = $('meta[name="uri"]').attr('content'); // llama a la url global que se encuantra en el head -> meta
    var url = uri + '/' + btn.value; // concateno la url global
    var token = $('#token-edit').val();

    swal({                                              // SweetAlert poner tal cual -> cambiar los textos
        title: "Estas Seguro?",
        text: "estas por eliminar información",
        icon: "warning",
        dangerMode: true,
        buttons: {
            cancel: 'Cancelar',
            confirm: 'Confirmar',
        },
    }).then(willDelete => {
        if (willDelete) {
            $.ajax({
                url: url,
                data: '_method=DELETE&_token=' + token,
                type: 'POST',
                success:function(res){
                    if(res){
                        loadAbmTable();
                        toastr["success"]("Datos Eliminados");
                    }else{
                        toastr["error"]("Error al eliminar Datos");
                    }
                }
            });
            swal("Eliminada!", "Datos eliminados con exito", "success");
        }
    });
}


//Traducción DataTable
var espanol = {
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ registros",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar:",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
}

