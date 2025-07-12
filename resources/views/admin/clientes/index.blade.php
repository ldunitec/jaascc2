@extends('adminlte::page')

@section('content_header')
    <h1><b>Listado de clientes </b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div id="colFormulario" class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">clientes registrados</h3>


                    <div class="card-tools">
                        <button id="btnMostrarFormulario" class="btn btn-primary">Crear cliente</button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="clientesTable"
                            class="table table-bordered table-hover table-striped table-sm  ">
                            <thead>
                                <tr>
                                    <th style="text-align: center">Nro</th>
                                    <th>Nombre del cliente</th>
                                    <th>Dni</th>
                                    <th>Correo</th>
                                    <th>Telefono</th>
                                    <th>Direccion</th>
                                    <th>Activo</th>
                                    <th style="text-align: center">Acción</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.card -->
        </div>

        {{-- formulario para crear nueva cliente  --}}
        <div class="col-md-4">
            <div id="cardFormulario" style="display: none;" class="card  card-primary">
                <div class="card-header">
                    <h3 class="card-title" id="formTitulo"></h3>
                    <div class="card-tools">
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form id="formCliente">
                        @csrf
                        <input type="hidden" id="id" name="id">
                        <div id="errores"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Nombre de la cliente</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="nombre" name="nombre"
                                            value="{{ old('nombre') }}" placeholder="Escriba aquí..." required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Correo</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="correo" name="correo"
                                            value="{{ old('correo') }}" placeholder="Escriba aquí..." required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">DNI</label><b> (*)</b>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-address-card"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="dni" name="dni"
                                                value="{{ old('dni') }}" placeholder="Escriba aquí..." required>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label for="">Telefono</label><b> (*)</b>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="telefono" name="telefono"
                                                value="{{ old('telefono') }}" placeholder="Escriba aquí..." required>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label for="">Direccion</label><b> (*)</b>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="direccion" name="direccion"
                                                value="{{ old('direccion') }}" placeholder="Escriba aquí..." required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                        <button type="button" id="btnCancelar" class="btn btn-secondary">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>

    </div>
@stop

@section('css')
    @include('admin.css.css')
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    @include('admin.clientes.parciales.js')
@stop
