@extends('index')

@section('title', 'Sign Up')

@section('content')
    <div class="container-fluid d-flex align-items-center justify-content-center" style="height: 60vh">

        <div class=" p-4 rounded shadow-md hover-shadow" style="width: 24rem;">
            <h2 class="text-2xl font-weight-bold mb-4">Registrar nueva cuenta</h2>


            @if (session('errorDB'))
                <div class="alert alert-danger" role="alert">
                    <span class="font-weight-bold">{{ session('errorDB') }}</span>
                </div>
            @endif
            <form method="POST" action="{{ route('PostRegister') }}">
                @csrf

                <div class="mb-4">
                    <label for="name" class="form-label text-sm font-weight-bold text-gray-600">Nombre</label>
                    <input type="text" id="name" name="name" value="{{ session('old')['name'] ?? null }}"
                        class="form-control mt-1" />

                    {!! session('error') ? session('error')->spanError('name') : null !!}
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label text-sm font-weight-bold text-gray-600">Correo
                        electrónico</label>
                    <input type="email" id="email" name="email" value="{{ session('old')['email'] ?? null }}"
                        class="form-control mt-1" />

                    {!! session('error') ? session('error')->spanError('email') : null !!}

                </div>

                <div class="mb-4">
                    <label for="password" class="form-label text-sm font-weight-bold text-gray-600">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control mt-1" />

                    {!! session('error') ? session('error')->spanError('password') : null !!}

                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label text-sm font-weight-bold text-gray-600">Confirmar
                        contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="form-control mt-1" />

                    {!! session('error') ? session('error')->spanError('password_confirmation') : null !!}

                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    Registrar cuenta
                </button>
            </form>
        </div>
    </div>
@endsection
