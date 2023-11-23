@php
    use App\Models\SessionManager;
    $name = SessionManager::read('user', 'name');
    $lastname = SessionManager::read('user', 'last_name');
    $email = SessionManager::read('user', 'email');
    $nif_cif = SessionManager::read('user', 'nif_cif');
    $phone = SessionManager::read('user', 'phoneNumber');
    if (null !== session('error')) {
        print_r(session('error'));
    }
@endphp
@extends('index')

@section('title', 'Add')
@section('content')

    <div class="d-flex flex-column m-auto border border-black p-2" style="width: 50%">
        <div class="m-auto mb-4">
            <h1>Nueva Tarea</h1>
        </div>
        <form class="m-auto" method="POST" action="{{ route('addPost') }}">
            @csrf
            <!-- Text input -->
            <div class="input-group mb-4">
                <span class="input-group-text " id="basic-addon1"><i class="bi bi-file-post"></i></span>
                <input type="text" class="form-control" name="description" placeholder="Titulo Tarea"
                    aria-label="Titulo Tarea" aria-describedby="basic-addon1">
            </div>
            <div class="row mb-4">
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-file-person"></i></span>
                        <input type="text" class="form-control" name="firstName" placeholder="Nombre" aria-label="Nombre"
                            aria-describedby="basic-addon1"
                            @if ($name) value="{{ $name }}" readonly @endif>
                    </div>
                </div>
                <div class="col">
                    <div class="input-group">
                        <input type="text" class="form-control" name="lastName" placeholder="Apellidos"
                            aria-label="Apellidos" aria-describedby="basic-addon1"
                            @if ($lastname) value="{{ $lastname }}" readonly @endif>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-file-post"></i></span>
                        <input type="text" class="form-control" name="nif_cif" placeholder="Nº Documento"
                            aria-label="Nº Documento" aria-describedby="basic-addon1"
                            @if ($nif_cif) value="{{ $nif_cif }}" readonly @endif>
                    </div>
                </div>
                <div class="col">
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-telephone-fill"></i></span>
                        <input type="tel" class="form-control" name="phoneNumber" placeholder="Numero de telefono"
                            aria-label="phoneNumber" aria-describedby="basic-addon1"
                            @if ($phone) value="{{ $phone }}" readonly @endif>
                    </div>
                </div>
            </div>
            <div class="input-group mb-4">
                <span class="input-group-text" id="basic-addon1"><i class="bi bi-envelope-at"></i></span>
                <input type="text" class="form-control" name="email" placeholder="Email" aria-label="email"
                    aria-describedby="basic-addon1"
                    @if ($email) value="{{ $email }}" readonly @endif>
            </div>
            <div class="row mb-4">
                <div class="col">
                    <select class="form-select" name="provinces" aria-label="Default select example">
                        <option selected>Provincia</option>
                        @foreach ($listProvinces as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-123"></i></span>
                        <input type="text" class="form-control" name="codigoPostal" placeholder="Codigo Postal"
                            aria-label="codigoPostal" aria-describedby="basic-addon1">
                    </div>
                </div>
            </div>
            <div class="input-group mb-4">
                <span class="input-group-text" id="basic-addon1"><i class="bi bi-houses-fill"></i></span>
                <input type="text" class="form-control" name="direccion" placeholder="Direccion" aria-label="direccion"
                    aria-describedby="basic-addon1">
            </div>
            <div class="row mb-4">
                <div class="col">
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-layers"></i></i></span>
                        <select class="form-select" name="status" aria-label="status">
                            <option selected disabled>Estado Tarea</option>
                            <option value="B">Esperando ser aprobada</option>
                            <option value="P">Pendiente</option>
                            <option value="R">Realizada</option>
                            <option value="C">Cancelada</option>
                        </select>
                    </div>
                </div>

                <div class="col">
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-geo-alt"></i></span>
                        <input type="text" class="form-control" name="location" placeholder="Localidad"
                            aria-label="location" aria-describedby="basic-addon1">
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-person-bounding-box"></i></span>
                        <input type="text" class="form-control" name="operario" placeholder="Operador a cargo"
                            aria-label="operario" aria-describedby="basic-addon1">
                    </div>
                </div>

                <div class="col">
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-calendar"></i></span>
                        <input type="date" class="form-control" name="date_task" aria-label="date_task"
                            aria-describedby="basic-addon1">
                    </div>
                </div>
            </div>
            <!-- Message input -->
            <div data-mdb-input-init class="form-outline mb-4">
                <label class="form-label" for="textarea">Additional information</label>
                <textarea class="form-control" name="antAnterior" id="textarea" rows="4"></textarea>
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-block mb-4">Place order</button>
        </form>
    </div>
@endsection
