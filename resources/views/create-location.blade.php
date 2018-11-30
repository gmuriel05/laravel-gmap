<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="uri" content="/{{$uri}}">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCC6emn13XOdxMhZAsbaGIgt2HcK3iKAoc"></script>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href=" {{ asset("assets/plugins/font-awesome/css/font-awesome.min.css") }}">

    <!--DataTables-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!--Toastr-->
    <link href="{{asset('assets/plugins/toastr/build/toastr.css')}}" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Gmap</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Cargar Ubicaciones<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('ver-mapa')}}">Ver Mapa</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid" style="margin-top: 25px; margin-bottom: 50px">
        <div class="row">
            <div class="col-12" id="tabla" style="display: none;margin-bottom: 50px" >
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="close btn-close float-right" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5>Ubicaciones</h5>

                    </div>

                    <div class="card-body">
                        <div class="col-xs-12 col-md-12 ">
                            <table id="table-abm" class="table table-hover" style="visibility:hidden;width: 100%">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Latitud</th>
                                    <th scope="col">Longitud</th>
                                    <th scope="col" style='width: 105px'>Acciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-header">
                        Guardar Ubicación
                    </div>
                    <div class="card-body">
                        <form id="form-create" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{csrf_token()}}" id="token-create" />
                            <div class="form-group">
                                <label for="exampleInputEmail1">Nombre</label>
                                <input type="text" class="form-control" name="name" id="name" aria-describedby="emailHelp">
                                <small class="pull-left" style="font-size: 11px;color: #ff6d5e;" id="error-name"></small>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Latitud</label>
                                <input type="text" id="txtLat" class="form-control" style="color:red" name="lat"  aria-describedby="emailHelp">
                                <small class="pull-left" style="font-size: 11px;color: #ff6d5e;" id="error-lat"></small>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Longitud</label>
                                <input type="text" id="txtLng" class="form-control" style="color:red" name="lng"  aria-describedby="emailHelp">
                                <small class="pull-left" style="font-size: 11px;color: #ff6d5e;" id="error-lng"></small>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-outline-primary" id="btn-ubications">Ubicaciones</button>
                                <button type="submit" class="btn btn-primary" id="btn-create">Guardar</button>
                                <button type="button" class="btn btn-primary  btn-primary " id="btn-preload-create"  style="display: none"><i class="fa fa-spinner fa-spin" style="font-size:18px;color:white"></i> Guardando..</button>
                            </div>
                        </form>
                    </div>
                </div>


            </div>
            <div class="col-9" >
                <div id="map_canvas" style="width: auto; height: 600px;"></div>
            </div>
        </div>
    </div>

    <!--MODAL EDIT-->
    @include('modals.edit')

    <!--DataTable-->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <!--Toastr-->
    <script src="{{asset('assets/plugins/toastr/toastr.js')}}"></script>

    <!--Sweetalert-->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script src="{{asset('assets/js/main.js')}}"></script>
</body>
</html>