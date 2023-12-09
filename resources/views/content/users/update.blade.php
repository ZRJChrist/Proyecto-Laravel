@extends('content.users')

@section('title', 'Update User')
@section('breadcrumb')
    <li class="breadcrumb-item" style="color: #E65100"><i class="fas fa-paragraph"></i> Update</li>
    <li class="breadcrumb-item active" aria-current="page"> {{ $user['id'] }}</li>

@endsection
@section('crudUsers')
    <div class="d-flex flex-column m-auto p-2 bg-white hover-shadow rounded-3" id="form" style="width: 70%">
        <div class="m-auto mb-4">
            <h1>Editar Usuario</h1>
        </div>
        <form class="m-auto needs-validation" method="POST" action="{{ route('updateUser', ['id' => $user['id']]) }}">
            @csrf

            <div class="row mb-4">
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-file-person"></i></span>
                        <input type="text" class="form-control " name="name" placeholder="Nombre" aria-label="Nombre"
                            aria-describedby="basic-addon1" value="{{ $user['name'] ?? null }}">
                    </div>
                    {{-- Error name --}}
                    {!! session('error') ? session('error')->spanError('name') : null !!}

                </div>
                <div class="col">
                    <div class="input-group">
                        <input type="text" class="form-control" name="last_name" placeholder="Apellidos"
                            aria-label="Apellidos" aria-describedby="basic-addon1" value="{{ $user['last_name'] ?? null }}">
                    </div>
                    {{-- Error last_name --}}
                    {!! session('error') ? session('error')->spanError('last_name') : null !!}
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-file-post"></i></span>
                        <input type="text" class="form-control" name="nif_cif" placeholder="Nº Documento"
                            aria-label="Nº Documento" aria-describedby="basic-addon1"
                            value="{{ $user['nif_cif'] ?? null }}">
                    </div>
                    {{-- Error NIF --}}
                    {!! session('error') ? session('error')->spanError('nif_cif') : null !!}
                </div>
                <div class="col">
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-telephone-fill"></i></span>
                        <input type="tel" class="form-control" name="phoneNumber" placeholder="Numero de telefono"
                            aria-label="phoneNumber" aria-describedby="basic-addon1"
                            value="{{ $user['phoneNumber'] ?? null }}">
                    </div>
                    {{-- Error phone --}}
                    {!! session('error') ? session('error')->spanError('phoneNumber') : null !!}
                </div>

            </div>
            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-envelope-at"></i></span>
                    <input type="text" class="form-control" name="email" placeholder="Email" aria-label="email"
                        aria-describedby="basic-addon1" value="{{ $user['email'] ?? null }}">
                </div>
                {!! session('error') ? session('error')->spanError('email') : null !!}
            </div>

            <!-- Submit button -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-center ">
                <button class="btn btn-dark" name="btnUpdateData" value="1" type="submit"
                    data-mdb-ripple-init>Actualizar</button>
                <button class="btn btn-warning" name="btnUpdateData" value="0" type="submit"
                    data-mdb-ripple-init>Cancelar</button>
            </div>
        </form>
        <div class="m-auto mt-5">
            <h1>Cambiar Contraseña</h1>
        </div>
        <form class="m-auto needs-validation align-content-center mt-2 mb-2" method="POST"
            action="{{ route('updateUser', ['id' => $user['id']]) }}">
            @csrf

            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-file-person"></i></span>
                    <input type="password" class="form-control " name="password" placeholder="Nueva Contraseña"
                        aria-label="password" aria-describedby="basic-addon1">
                </div>
                {!! session('error') ? session('error')->spanError('password') : null !!}
            </div>
            <div class="mb-5">
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-file-person"></i></span>
                    <input type="password" class="form-control " name="confirm_password"
                        placeholder="Confirmar Contraseña" aria-label="confirm_password" aria-describedby="basic-addon1">
                </div>
                {!! session('error') ? session('error')->spanError('confirm_password') : null !!}
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-center ">
                <button class="btn btn-dark" name="btnUpdatePass" value="1" type="submit"
                    data-mdb-ripple-init>Actualizar
                    Contraseña</button>
            </div>
        </form>
    </div>
@endsection
