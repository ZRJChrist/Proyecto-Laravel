@extends('index')

@section('title', 'Sign In')

@section('content')

    <div class="container-fluid d-flex align-items-center p-0 w-75 bg-white rounded-3 justify-content-center hover-shadow"
        style="height: 50%">
        <div class="col text-center w-100 dec-login">
            <div class="p-4 m-auto mt-5">
                <span class="display-4 fw-bold text-white" style="background-color: #ffc300">Hola!!</span>
                <h1 class="fw-light text-white">Bienvenido de nuevo</h1>
            </div>
        </div>
        <div class="col">
            <div class="p-4 m-auto" style="width: 24em;">
                <h2 class="fw-bold mb-4">Iniciar sesión</h2>

                <!-- Formulario de inicio de sesión -->
                <form method="POST" action="{{ route('PostLogin') }}">
                    @csrf

                    <!-- Campo de nombre de usuario -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-weight-bold text-gray-600">Email</label>
                        <input type="text" id="email" name="email"
                            class="form-control border-0 border-warning border-bottom mt-1" />

                        @if (session('error'))
                            <div class="alert alert-danger p-1 mt-1" role="alert">
                                <span class="font-weight-bold">
                                    <i class="fas fa-circle-exclamation"></i>
                                    {{ session('error')->getError('email') }}</span>
                            </div>
                        @endif

                    </div>
                    <!-- Campo de contraseña -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-weight-bold text-gray-600">Contraseña</label>
                        <input type="password" id="password" name="password"
                            class="form-control border-0 border-warning border-bottom mt-1" />
                        @if (session('error'))
                            <div class="alert alert-danger p-1 mt-1" role="alert">
                                <span class="font-weight-bold">
                                    <i class="fas fa-circle-exclamation"></i>
                                    {{ session('error')->getError('password') }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Botón de inicio de sesión -->
                    <button type="submit" class="btn btn btn-secondary ">
                        <i class="fa-solid fa-right-to-bracket px-1"></i> Iniciar sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
