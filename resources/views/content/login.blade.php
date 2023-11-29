@extends('index')

@section('title', 'Sign In')

@section('content')
    <div class="container-fluid d-flex align-items-center justify-content-center" style="height: auto">

        <div class="p-4 shadow-md mt-5 m-auto hover-shadow " style="width: 24em;">
            <h2 class="text-2xl font-weight-bold mb-4">Iniciar sesión</h2>

            <!-- Formulario de inicio de sesión -->
            <form method="POST" action="{{ route('PostLogin') }}">
                @csrf

                <!-- Campo de nombre de usuario -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-weight-bold text-gray-600">Email</label>
                    <input type="text" id="email" name="email" class="form-control mt-1" />

                    @if (session('email'))
                        <div class="p-2 mb-4 mt-1 text-sm text-white bg-danger rounded-lg" role="alert">
                            <span class="font-weight-bold">{{ session('email') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Campo de contraseña -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-weight-bold text-gray-600">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control mt-1" />

                    @if (session('password'))
                        <div class="p-2 mb-4 mt-1 text-sm text-white bg-danger rounded-lg" role="alert">
                            <span class="font-weight-bold">{{ session('password') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Botón de inicio de sesión -->
                <button type="submit" class="btn btn-primary btn-block">
                    Iniciar sesión
                </button>
            </form>
        </div>
    </div>
@endsection
