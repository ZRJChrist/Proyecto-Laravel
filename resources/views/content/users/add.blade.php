@extends('content.users')

@section('title', 'Add User')
@section('breadcrumb')
    <li class="breadcrumb-item" style="color: #858c2c"><i class="fa-solid fa-plus"></i> Add User</li>

@endsection

@section('crudUsers')
    <div class="d-flex flex-column m-auto p-2 hover-shadow " id="form" style="width: 70%">
        <div class="m-auto mb-4">
            <h1>Nuevo Usuario</h1>
        </div>
        @if (session('errorDB'))
            <div class="alert alert-danger" role="alert">
                <span class="font-weight-bold">{{ session('errorDB') }}</span>
            </div>
        @endif
        <form class="m-auto needs-validation" method="POST" action="{{ route('createUsers') }}">
            @csrf

            <div class="row mb-4">
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-file-person"></i></span>
                        <input type="text" class="form-control " name="name" placeholder="Nombre" aria-label="Nombre"
                            aria-describedby="basic-addon1" value="{{ session('old')['name'] ?? null }}">
                    </div>
                    {{-- Error name --}}
                    {!! session('error') ? session('error')->spanError('name') : null !!}

                </div>
                <div class="col">
                    <div class="input-group">
                        <input type="text" class="form-control" name="last_name" placeholder="Apellidos"
                            aria-label="Apellidos" aria-describedby="basic-addon1"
                            value="{{ session('old')['last_name'] ?? null }}">
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
                            value="{{ session('old')['nif_cif'] ?? null }}">
                    </div>
                    {{-- Error NIF --}}
                    {!! session('error') ? session('error')->spanError('nif_cif') : null !!}
                </div>
                <div class="col">
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-telephone-fill"></i></span>
                        <input type="tel" class="form-control" name="phoneNumber" placeholder="Numero de telefono"
                            aria-label="phoneNumber" aria-describedby="basic-addon1"
                            value="{{ session('old')['phoneNumber'] ?? null }}">
                    </div>
                    {{-- Error phone --}}
                    {!! session('error') ? session('error')->spanError('phoneNumber') : null !!}
                </div>

            </div>
            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-envelope-at"></i></span>
                    <input type="text" class="form-control" name="email" placeholder="Email" aria-label="email"
                        aria-describedby="basic-addon1" value="{{ session('old')['email'] ?? null }}">
                </div>
                {{-- Error Email --}}
                {!! session('error') ? session('error')->spanError('email') : null !!}
            </div>
            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-pen-ruler"></i></span>
                    <select class="form-select" name="role" aria-label="role" style="height: 2.2em;">
                        <option {{ session('old.role') == '0' ? 'selected' : '' }} value="0">Operario</option>
                        <option {{ session('old.role') == '1' ? 'selected' : '' }} value="1">Administrador</option>
                    </select>
                </div>
                {{-- Error role --}}
                {!! session('error') ? session('error')->spanError('role') : null !!}
            </div>
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
                    <input type="password" class="form-control " name="password_confirmation"
                        placeholder="Confirmar Contraseña" aria-label="password_confirmation"
                        aria-describedby="basic-addon1">
                </div>
                {!! session('error') ? session('error')->spanError('password_confirmation') : null !!}
            </div>

            <!-- Submit button -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-center ">
                <button class="btn btn-dark" type="submit" data-mdb-ripple-init>Agregar</button>
            </div>
        </form>
    </div>
@endsection
