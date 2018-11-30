@section('edit')
    <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-green text-black-50">
                    <h5 class="modal-title" id="exampleModalLabel">Editar</h5>
                    <button type="button" class="close close-edit" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-edit" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden"  value="" id="id-edit">
                    <input type="hidden" name="_token" value="{{csrf_token()}}" id="token-edit" />
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="recipient-name" class="col-form-label">Name:</label>
                                <input type="text" class="form-control" name="name" id="name-edit">
                                <small class="pull-left" style="font-size: 11px;color: #ff6d5e;" id="error-name-edit"></small>
                                <div class="validation"><small style="color: #ff6d5e;">{{$errors->first('name')}}</small></div>
                            </div>
                            <div class="form-group col-12">
                                <label for="recipient-name" class="col-form-label">Latitud:</label>
                                <input type="text" class="form-control" name="lat" id="lat-edit">
                                <small class="pull-left" style="font-size: 11px;color: #ff6d5e;" id="error-lat-edit"></small>
                                <div class="validation"><small style="color: #ff6d5e;">{{$errors->first('lat')}}</small></div>
                            </div>
                            <div class="form-group col-12">
                                <label for="recipient-name" class="col-form-label">Longitud:</label>
                                <input type="text" class="form-control" name="lng" id="lng-edit">
                                <small class="pull-left" style="font-size: 11px;color: #ff6d5e;" id="error-lng-edit"></small>
                                <div class="validation"><small style="color: #ff6d5e;">{{$errors->first('lng')}}</small></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-xs-12">
                            <button type="button" class="btn btn-secondary" id="btn-close-edit" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary  btn-primary " id="btn-preload-edit"  style="display: none"><i class="fa fa-spinner fa-spin" style="font-size:18px;color:white"></i> Actualizando..</button>
                            <button type="submit" class="btn btn-primary" id="btn-edit">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@show