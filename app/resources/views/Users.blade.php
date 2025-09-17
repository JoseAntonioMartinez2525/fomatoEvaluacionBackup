<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr"
    data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template">

<title>Formato Evaluación</title>

<body>
    <div class="layout-wrapper layout-content-navbar  ">
        <x-general-header />
        <div class="layout-container">
            
            <div class="layout-page">
               
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">


                        <h4 class="py-3 mb-4">
                            <span class="text-muted fw-light">Panel de Usuarios</span>
                            <button type="button" class="btn btn-success float-end me-sm-2 me-1" data-bs-toggle="modal"
                                data-bs-target="#agregarUsuarioModal"><i class="ti ti-plus"></i></button>
                        </h4>
                        @if (session("correcto"))
                            <div class="alert alert-success">{{session("correcto")}}</div>
                        @endif

                        @if (session("incorrecto"))
                            <div class="alert alert-danger">{{session("incorrecto")}}</div>
                        @endif
                        <div class="card">
                            <div class="table-responsive text-nowrap">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#ID</th>
                                            <th>Correo</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($datos as $item)
                                            <tr>
                                                <td>#{{$item->id}}</td>
                                                <td>{{$item->email}}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                            data-bs-toggle="dropdown">
                                                            <i class="ti ti-dots-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                                data-bs-target="#consultaUsuario{{$item->id}}">
                                                                <i class="menu-icon tf-icons ti ti-file-description"></i>
                                                                Consultar
                                                            </a>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                                data-bs-target="#editarUsuario{{$item->id}}">
                                                                <i class="ti ti-pencil me-2"></i> Editar
                                                            </a>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                                data-bs-target="#eliminarUsuarioModal{{$item->id}}">
                                                                <i class="ti ti-trash me-2"></i> Eliminar
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-backdrop fade"></div>
            </div>
        </div>
    </div>
    <!------------------------------------------------------Modal de Agregar Usuario--------------------------------------------------->
    <div class="modal fade" id="agregarUsuarioModal" tabindex="-1" data-bs-backdrop="static" role="dialog"
        aria-labelledby="agregarUsuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarUsuarioModalLabel">Agregar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('users.create')}}" class="form-inline"
                        enctype=multipart/form-data>
                        @csrf
                        <div class="card-body">
 
                            <div class="row mb-3">
                                <label for="Email" class="col-sm-3 col-form-label text-sm-end">Correo
                                    electrónico</label>
                                <div class="col-sm-9">
                                    <input name="email" type="email" class="form-control"
                                        placeholder="Ingrese el correo electrónico" minlength="3" required />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="Contraseña" class="col-sm-3 col-form-label text-sm-end">Contraseña</label>
                                <div class="col-sm-9">
                                    <input name="password" type="password" class="form-control"
                                        placeholder="Ingrese la contraseña" minlength="3" required />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button id="agregarUsuario" type="submit" class="btn btn-success">Agregar Usuario</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-------------------------------------------------------Modal-consulta------------------------------------------------------------->
    @foreach($datos as $item)
    <div class="modal fade" id="consultaUsuario{{$item->id}}" tabindex="-1" role="dialog" data-bs-backdrop="static"
        aria-labelledby="consulta-Usuario" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="consulta-Usuario">Detalles del Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-7 text-left">
                        <p>ID: {{$item->id}}</p>
                        <p>Nombre: {{$item->name}}</p>
                        <p>Primer Apellido: {{$item->first_name}}</p>
                        <p>Segundo Apellido: {{$item->last_name}}</p>
                        <p>Teléfono: {{$item->phone}}</p>
                        <p>Correo electrónico: {{$item->email}}</p>
                        <p type="password">Contraseña: {{$item->password}}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    <!--------------------------------------------------------Modal de eliminación------------------------------------------------------>
    @foreach($datos as $item)
        <div class="modal fade" id="eliminarUsuarioModal{{$item->id}}" tabindex="-1" role="dialog"
            aria-labelledby="eliminarUsuarioModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eliminarUsuarioModalLabel">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que quieres eliminar este Usuario?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <a href="{{ route("users.delete", $item->id)}}" class="btn btn-danger">Eliminar</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-------------------------------------------------------Modal de edición----------------------------------------------------------->
    @foreach($datos as $item)
    <div class="modal fade" id="editarUsuario{{$item->id}}" tabindex="-1" role="dialog"
        aria-labelledby="editarUsuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarUsuarioModalLabel">Editar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route(" users.update") }}" class="form-inline">
                        @csrf
                        <input type="hidden" name="id" value="{{$item->id}}">
                        <div class="card-body">
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-sm-end">ID</label>
                                <div class="col-sm-9">
                                    <input type="number" name="id" class="form-control" value="{{$item->id}}"
                                        readonly />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="name" class="col-sm-3 col-form-label text-sm-end">Nombre</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" class="form-control" placeholder="Nombre del usuario"
                                        value="{{$item->name}}" minlength="3" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]+"
                                        title="Ingrese solo letras" required />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="first_name" class="col-sm-3 col-form-label text-sm-end">Primer
                                    Apellido</label>
                                <div class="col-sm-9">
                                    <input name="first_name" type="text" class="form-control"
                                        placeholder="Primer apellido del usuario" value="{{$item->first_name}}"
                                        minlength="3" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]+" title="Ingrese solo letras"
                                        required />

                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="last_name" class="col-sm-3 col-form-label text-sm-end">Segundo
                                    Apellido</label>
                                <div class="col-sm-9">
                                    <input name="last_name" type="text" class="form-control"
                                        placeholder="Segundo apellido del usuario" value="{{$item->last_name}}"
                                        minlength="3" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]+" title="Ingrese solo letras"
                                        required />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="phone" class="col-sm-3 col-form-label text-sm-end">Telefóno</label>
                                <div class="col-sm-9">
                                    <input name="phone" type="tel" class="form-control"
                                        placeholder="Telefóno del usuario" value="{{$item->phone}}" pattern="[0-9]{10}"
                                        title="Ingrese un número de 10 dígitos" required />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email" class="col-sm-3 col-form-label text-sm-end">Correo
                                    electrónico</label>
                                <div class="col-sm-9">
                                    <input name="email" type="email" class="form-control"
                                        placeholder="Correo electrónico del usuario" value="{{$item->email}}"
                                        minlength="3" required />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="password" class="col-sm-3 col-form-label text-sm-end">Contraseña</label>
                                <div class="col-sm-9">
                                    <input name="password" type="password" class="form-control"
                                        placeholder="Contraseña del usuario" value="{{$item->password}}" minlength="3"
                                        required />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success" data-bs-dismiss="modal">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @include('scripts')
</body>

</html>