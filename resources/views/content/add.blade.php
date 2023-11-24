@php
    $error = false;
    if (null !== session('error')) {
        $error = true;
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
            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text " id="basic-addon1"><i class="bi bi-file-post"></i></span>
                    <input type="text" class="form-control" name="description" placeholder="Titulo Tarea"
                        aria-label="Titulo Tarea" aria-describedby="basic-addon1">
                </div>
                {{-- Error Description --}}
                @if ($error)
                    {!! session('error')->spanError('description') !!}
                @endif
            </div>
            <div class="row mb-4">
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-file-person"></i></span>
                        <input type="text" class="form-control" name="firstName" placeholder="Nombre" aria-label="Nombre"
                            aria-describedby="basic-addon1">
                    </div>
                    {{-- Error FirstName --}}
                    @if ($error)
                        {!! session('error')->spanError('firstName') !!}
                    @endif
                </div>
                <div class="col">
                    <div class="input-group">
                        <input type="text" class="form-control" name="lastName" placeholder="Apellidos"
                            aria-label="Apellidos" aria-describedby="basic-addon1">
                    </div>
                    {{-- Error LastName --}}
                    @if ($error)
                        {!! session('error')->spanError('lastName') !!}
                    @endif
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-file-post"></i></span>
                        <input type="text" class="form-control" name="nif_cif" placeholder="Nº Documento"
                            aria-label="Nº Documento" aria-describedby="basic-addon1">
                    </div>
                    {{-- Error NIF --}}
                    @if ($error)
                        {!! session('error')->spanError('nif_cif') !!}
                    @endif
                </div>
                <div class="col">
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-telephone-fill"></i></span>
                        <input type="tel" class="form-control" name="phoneNumber" placeholder="Numero de telefono"
                            aria-label="phoneNumber" aria-describedby="basic-addon1">
                    </div>
                    {{-- Error phone --}}
                    @if ($error)
                        {!! session('error')->spanError('phoneNumber') !!}
                    @endif
                </div>

            </div>
            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-envelope-at"></i></span>
                    <input type="text" class="form-control" name="email" placeholder="Email" aria-label="email"
                        aria-describedby="basic-addon1">
                </div>
                @if ($error)
                    {!! session('error')->spanError('email') !!}
                @endif
            </div>


            <div class="row mb-4">
                <div class="col">
                    <select class="form-select" name="provinces" aria-label="Default select example">
                        <option selected value="null">Provincia</option>
                        @foreach ($listProvinces as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    {{-- Error Provincia --}}
                    @if ($error)
                        {!! session('error')->spanError('provinces') !!}
                    @endif
                </div>
                <div class="col">
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-123"></i></span>
                        <input type="text" class="form-control" name="codigoPostal" placeholder="Codigo Postal"
                            aria-label="codigoPostal" aria-describedby="basic-addon1">
                    </div>
                    {{-- Error codigo postal --}}
                    @if ($error)
                        {!! session('error')->spanError('codigoPostal') !!}
                    @endif
                </div>
            </div>
            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-houses-fill"></i></span>
                    <input type="text" class="form-control" name="direccion" placeholder="Direccion"
                        aria-label="direccion" aria-describedby="basic-addon1">
                </div>
                {{-- Error address --}}
                @if ($error)
                    {!! session('error')->spanError('direccion') !!}
                @endif
            </div>
            <div class="row mb-4">
                <div class="col">
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-layers"></i></i></span>
                        <select class="form-select" name="status" aria-label="status">
                            <option selected value="null">Estado Tarea</option>
                            <option value="B">Esperando ser aprobada</option>
                            <option value="P">Pendiente</option>
                            <option value="R">Realizada</option>
                            <option value="C">Cancelada</option>
                        </select>
                    </div>
                    {{-- Error Status --}}
                    @if ($error)
                        {!! session('error')->spanError('status') !!}
                    @endif
                </div>

                <div class="col">
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-geo-alt"></i></span>
                        <input type="text" class="form-control" name="location" placeholder="Localidad"
                            aria-label="location" aria-describedby="basic-addon1">
                    </div>
                    {{-- Error location --}}
                    @if ($error)
                        {!! session('error')->spanError('location') !!}
                    @endif
                </div>
            </div>
            <div class="row mb-4">
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-person-bounding-box"></i></span>
                        <input type="text" class="form-control" name="operario" placeholder="Operador a cargo"
                            aria-label="operario" aria-describedby="basic-addon1">
                    </div>
                    {{-- Error Operador --}}
                    @if ($error)
                        {!! session('error')->spanError('operario') !!}
                    @endif
                </div>

                <div class="col">
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-calendar"></i></span>
                        <input type="date" class="form-control" name="date_task" aria-label="date_task"
                            aria-describedby="basic-addon1">
                    </div>
                    {{-- Error Date --}}
                    @if ($error)
                        {!! session('error')->spanError('date_task') !!}
                    @endif
                </div>
            </div>
            <!-- Message input -->
            <div data-mdb-input-init class="form-outline mb-4">
                <label class="form-label" for="textarea">Additional information</label>
                <textarea class="form-control" name="inf_task" id="textarea" rows="4"></textarea>
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-block mb-4">Place order</button>
        </form>
    </div>
@endsection
