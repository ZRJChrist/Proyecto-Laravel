@extends('index')

@section('title', 'Sign Up')

@section('content')
    <div class="container-fluid d-flex align-items-center justify-content-center" style="height: 80vh">

        <div class=" p-4 rounded shadow-md" style="width: 24rem; border: 2px solid black;">
            <h2 class="text-2xl font-weight-bold mb-4">Registrar nueva cuenta</h2>

            @if (session('error'))
                @php
                    extract(session('error'));
                @endphp
            @endif

            @if (isset($errorDB))
                <div class="p-2 mb-4 mt-1 text-sm text-white bg-danger rounded-lg" role="alert">
                    <span class="font-weight-bold">{{ $errorDB }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('PostRegister') }}">
                @csrf

                <div class="mb-4">
                    <label for="name" class="form-label text-sm font-weight-bold text-gray-600">Nombre</label>
                    <input type="text" id="name" name="name" class="form-control mt-1" />

                    @if (isset($name))
                        <div class="p-2 mb-4 mt-1 text-sm text-white bg-danger rounded-lg" role="alert">
                            <span class="font-weight-bold">{{ $name }}</span>
                        </div>
                    @endif
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label text-sm font-weight-bold text-gray-600">Correo
                        electrónico</label>
                    <input type="email" id="email" name="email" class="form-control mt-1" />

                    @if (isset($email))
                        <div class="p-2 mb-4 mt-1 text-sm text-white bg-danger rounded-lg" role="alert">
                            <span class="font-weight-bold">{{ $email }}</span>
                        </div>
                    @endif
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label text-sm font-weight-bold text-gray-600">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control mt-1" />

                    @if (isset($password))
                        <div class="p-2 mb-4 mt-1 text-sm text-white bg-danger rounded-lg" role="alert">
                            <span class="font-weight-bold">{{ $password }}</span>
                        </div>
                    @endif
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label text-sm font-weight-bold text-gray-600">Confirmar
                        contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="form-control mt-1" />

                    @if (isset($passwordConfirmation))
                        <div class="p-2 mb-4 mt-1 text-sm text-white bg-danger rounded-lg" role="alert">
                            <span class="font-weight-bold">{{ $passwordConfirmation }}</span>
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    Registrar cuenta
                </button>
            </form>
        </div>
    </div>
@endsection
