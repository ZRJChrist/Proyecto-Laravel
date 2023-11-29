@extends('index')

@section('title', 'Sign Up')

@section('content')
    <div class="container-fluid d-flex bg-white w-75 hover-shadow align-items-center justify-content-center p-0"
        style="height:70vh">
        <div class="col text-center w-100 dec-register">
            <div class="m-auto mt-5">
                <span class="display-3 fw-bold text-white" style="background-color: #ffc300">Bienvenido!!</span>
            </div>
        </div>
        <div class="col">
            <div class=" p-4 m-auto" style="width: 24rem;">
                <h2 class="fw-bold mb-4">Crear una cuenta</h2>

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
                            class="form-control border-0 border-warning border-bottom mt-1" />

                        @if (session('error'))
                            <div class="alert alert-danger p-1 mt-1" role="alert">
                                <span class="font-weight-bold">
                                    <i class="fas fa-circle-exclamation"></i>
                                    {{ session('error')->getError('name') }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label text-sm font-weight-bold text-gray-600">Correo
                            electrónico</label>
                        <input type="email" id="email" name="email" value="{{ session('old')['email'] ?? null }}"
                            class="form-control border-0 border-warning border-bottom mt-1" />

                        @if (session('error'))
                            <div class="alert alert-danger p-1 mt-1" role="alert">
                                <span class="font-weight-bold">
                                    <i class="fas fa-circle-exclamation"></i>
                                    {{ session('error')->getError('email') }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label text-sm font-weight-bold text-gray-600">Contraseña</label>
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

                    <div class="mb-4">
                        <label for="password_confirmation"
                            class="form-label text-sm font-weight-bold text-gray-600">Confirmar
                            contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-control border-0 border-warning border-bottom mt-1" />

                        @if (session('error'))
                            <div class="alert alert-danger p-1 mt-1" role="alert">
                                <span class="font-weight-bold">
                                    <i class="fas fa-circle-exclamation"></i>
                                    {{ session('error')->getError('password_confirmation') }}</span>
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-secondary  ">
                        <i class="fa-solid fa-user-plus px-1"></i> Registrar cuenta
                    </button>
                </form>
            </div>
        </div>

    </div>
@endsection
