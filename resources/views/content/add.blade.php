@extends('index')

@section('title', 'Add')
@section('content')
    <div class="d-flex flex-column m-auto p-2 hover-shadow " id="form" style="width: 50%">
        <div class="m-auto mb-4">
            <h1>Nueva Tarea</h1>
        </div>
        <form class="m-auto needs-validation" novalidate method="POST" action="{{ route('addPost') }}">
            @csrf
            <!-- Text input -->
            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-file-post"></i></span>
                    <input type="text" class="form-control" name="description" placeholder="Titulo Tarea"
                        aria-label="Titulo Tarea"
                        aria-describedby="basic-addon1"value="{{ session('old.description') ?? null }}">
                </div>
                {{-- Error Description --}}
                {!! session('error') ? session('error')->spanError('description') : null !!}

            </div>
            <div class="row mb-4">
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-file-person"></i></span>
                        <input type="text" class="form-control " name="firstName" placeholder="Nombre"
                            aria-label="Nombre" aria-describedby="basic-addon1"
                            value="{{ session('old.firstName') ?? null }}">
                    </div>
                    {{-- Error FirstName --}}
                    {!! session('error') ? session('error')->spanError('firstName') : null !!}

                </div>
                <div class="col">
                    <div class="input-group">
                        <input type="text" class="form-control" name="lastName" placeholder="Apellidos"
                            aria-label="Apellidos" aria-describedby="basic-addon1"
                            value="{{ session('old.lastName') ?? null }}">
                    </div>
                    {{-- Error LastName --}}
                    {!! session('error') ? session('error')->spanError('lastName') : null !!}
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-file-post"></i></span>
                        <input type="text" class="form-control" name="nif_cif" placeholder="Nº Documento"
                            aria-label="Nº Documento" aria-describedby="basic-addon1"
                            value="{{ session('old.nif_cif') ?? null }}">
                    </div>
                    {{-- Error NIF --}}
                    {!! session('error') ? session('error')->spanError('nif_cif') : null !!}
                </div>
                <div class="col">
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-telephone-fill"></i></span>
                        <input type="tel" class="form-control" name="phoneNumber" placeholder="Numero de telefono"
                            aria-label="phoneNumber" aria-describedby="basic-addon1"
                            value="{{ session('old.phoneNumber') ?? null }}">
                    </div>
                    {{-- Error phone --}}
                    {!! session('error') ? session('error')->spanError('phoneNumber') : null !!}
                </div>

            </div>
            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-envelope-at"></i></span>
                    <input type="text" class="form-control" name="email" placeholder="Email" aria-label="email"
                        aria-describedby="basic-addon1" value="{{ session('old.email') ?? null }}">
                </div>
                {!! session('error') ? session('error')->spanError('email') : null !!}
            </div>


            <div class="row mb-4">
                <div class="col">
                    <select class="form-select" name="province_id" aria-label="Default select example">
                        <option selected value="null">Provincia</option>
                        @foreach ($listProvinces as $key => $value)
                            @if ($key < 10)
                                <option value="0{{ $key }}">{{ $value }}</option>
                            @else
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>
                    {{-- Error Provincia --}}
                    {!! session('error') ? session('error')->spanError('province_id') : null !!}
                </div>
                <div class="col">
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-123"></i></span>
                        <input type="text" class="form-control" name="codigoPostal" placeholder="Codigo Postal"
                            aria-label="codigoPostal" aria-describedby="basic-addon1"
                            value="{{ session('old.codigoPostal') ?? null }}">
                    </div>
                    {{-- Error codigo postal --}}
                    {!! session('error') ? session('error')->spanError('codigoPostal') : null !!}
                </div>
            </div>
            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-houses-fill"></i></span>
                    <input type="text" class="form-control" name="direccion" placeholder="Direccion"
                        aria-label="direccion" aria-describedby="basic-addon1"
                        value="{{ session('old.direccion') ?? null }}">
                </div>
                {{-- Error address --}}
                {!! session('error') ? session('error')->spanError('direccion') : null !!}
            </div>
            <div class="row mb-4">
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-layers"></i></span>
                        <select class="form-select" name="status_task" aria-label="status" style="height: 2.2em;">
                            <option selected value="null">Estado Tarea</option>
                            <option value="B">Esperando ser aprobada</option>
                            <option value="P">Pendiente</option>
                            <option value="R">Realizada</option>
                            <option value="C">Cancelada</option>
                        </select>
                    </div>
                    {{-- Error Status --}}
                    {!! session('error') ? session('error')->spanError('status_task') : null !!}
                </div>

                <div class="col">
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-geo-alt"></i></span>
                        <input type="text" class="form-control" name="location" placeholder="Localidad"
                            aria-label="location" aria-describedby="basic-addon1"
                            value="{{ session('old.location') ?? null }}">
                    </div>
                    {{-- Error location --}}
                    {!! session('error') ? session('error')->spanError('location') : null !!}
                </div>
            </div>
            <div class="row mb-4">
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i
                                class="bi bi-person-bounding-box"></i></span><select class="form-select" name="operario"
                            aria-label="operario" style="height: 2.2em;">
                            <option selected value="null">Operario</option>
                            @foreach ($operarios as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Error Operador --}}
                    {!! session('error') ? session('error')->spanError('operario') : null !!}
                </div>

                <div class="col">
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-calendar"></i></span>
                        <input type="date" class="form-control" name="date_task" aria-label="date_task"
                            aria-describedby="basic-addon1" value="{{ session('old.date_task') ?? null }}">
                    </div>
                    {{-- Error Date --}}
                    {!! session('error') ? session('error')->spanError('date_task') : null !!}
                </div>
            </div>
            <!-- Message input -->
            <div class="form-floating mb-4">
                <textarea class="form-control" placeholder="Informacion de la tarea" name="inf_task" id="floatingTextarea2"
                    style="height: 100px">{{ session('old.inf_task') ?? null }}</textarea>
                <label for="floatingTextarea2">Inf. Anterior</label>
            </div>
            <!-- Submit button -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-center ">
                <button class="btn btn-dark" type="submit" name="btnFrom" value="1" data-mdb-ripple-init>Agregar
                    Tarea</button>
                <button class="btn btn-warning" type="submit" name="btnFrom" value="0"
                    data-mdb-ripple-init>Cancelar</button>
            </div>
        </form>
    </div>
@endsection
