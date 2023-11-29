<div class="p-4" style="width: 280px; height:100vh">
    <a href="{{ route('listTask') }}"
        class="d-flex align-items-center pb-3 mb-3 link-dark text-decoration-none border-bottom">
        <div class="d-flex flex-column position-relative">
            <div class="position-relative" style="left:70%">
                <i class="bi bi-buildings-fill fa-3x"></i>
            </div>
            <div>
                <h1 class="fw-semibold">Bunglebuild</h1>
            </div>
        </div>
    </a>
    @if (!Utils::isLogIn())
        <ul class="list-unstyled fw-normal  pb-1">
            <li class="mb-2">
                <a class="btn btn-outline-dark btn-rounded btn-log" href="{{ route('login') }}" role="button">
                    <i class="fa-solid fa-right-to-bracket px-1"></i>Sign In</a>
            </li>
            <li class="mb-2">
                <a class="btn btn-outline-dark btn-rounded btn-log" href="{{ route('register') }}" role="button">
                    <i class="fa-solid fa-user-plus px-1"></i>Sign Up</a>
            </li>
        </ul>
    @else
        <ul class="list-unstyled fw-normal  pb-1">
            <li class="mb-2">
                <p class="d-inline-flex gap-1">
                    <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample" role="button"
                        aria-controls="collapseExample">
                        Tareas
                    </a>
                </p>
                <div class="collapse" id="collapseExample">
                    <ul class="list-unstyled fw-normalpb-1">
                        <li>
                            <a class="d-flex align-items-center text-white text-decoration-none"
                                href="{{ route('listTask') }}">
                                Ver todas las tareas
                            </a>
                        </li>
                        <li>
                            <div class="dropdown">
                                <a href="#"
                                    class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                                    id="dropdownStatus" data-bs-toggle="dropdown">
                                    Estado
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark text-small shadow"
                                    aria-labelledby="dropdownStatus" style="">
                                    <li><a class="dropdown-item"
                                            href="{{ route('listTask', ['status' => 'B']) }}">Esperando</a>
                                    </li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('listTask', ['status' => 'P']) }}">Pendientes</a>
                                    </li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('listTask', ['status' => 'R']) }}">Realizadas</a>
                                    </li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('listTask', ['status' => 'C']) }}">Canceladas</a>
                                    </li>

                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="border-bottom"></li>
            <li class="my-5">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                        id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://github.com/mdo.png" alt="" width="32" height="32"
                            class="rounded-circle me-2">
                        <strong>mdo</strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1"
                        style="">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Sign out</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    @endif
</div>
