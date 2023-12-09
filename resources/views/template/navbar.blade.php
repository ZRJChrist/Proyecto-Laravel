<div class="p-4" style="width: 280px; height:100vh">
    <a href="{{ route('readTasks') }}"
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
        <ul class="list-unstyled fw-normal pb-1">
            <li class="mb-2">
                <a class="btn btn-outline-dark btn-rounded btn-log" href="{{ route('login') }}" role="button">
                    <i class="fa-solid fa-right-to-bracket px-1"></i>Sign In</a>
            </li>
        </ul>
    @else
        <ul class="list-unstyled fw-normal pb-1">
            <li class="mb-2">
                <p class="d-inline-flex gap-1">
                    <a class="d-flex align-items-center text-dark text-decoration-none" data-bs-toggle="collapse"
                        href="#collapseExample" role="button" aria-controls="collapseExample">
                        <i class="fa-solid fa-angle-right px-1"></i> Tasks <i class="fa-solid fa-list-check ps-2"></i>
                    </a>
                </p>
                <div class="collapse" id="collapseExample">
                    <ul class="list-unstyled fw-normal pb-1">
                        <li>
                            <a class="d-flex align-items-center text-dark text-decoration-none ps-4"
                                href="{{ route('readTasks') }}">
                                <i class="fa-solid fa-list-check pe-2"></i> View All Task
                            </a>
                        </li>
                        <li>
                            <div class="dropdown">
                                <a href="#"
                                    class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle ps-4"
                                    id="dropdownStatus" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-bars-progress pe-2"></i>Status
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark text-small shadow"
                                    aria-labelledby="dropdownStatus" style="">
                                    <li><a class="dropdown-item"
                                            href="{{ route('readTasks', ['status_task' => 'B']) }}">Esperando</a>
                                    </li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('readTasks', ['status_task' => 'P']) }}">Pendientes</a>
                                    </li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('readTasks', ['status_task' => 'R']) }}">Realizadas</a>
                                    </li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('readTasks', ['status_task' => 'C']) }}">Canceladas</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown">
                                <a href="#"
                                    class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle ps-4"
                                    id="dropdownStatus" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-users-gear pe-2"></i>Operators
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark text-small shadow"
                                    aria-labelledby="dropdownStatus" style="">
                                    @foreach (Utils::getOperators() as $key => $operator)
                                        <li><a class="dropdown-item"
                                                href="{{ route('readTasks', ['operario' => $key]) }}">{{ $operator }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>

            @if (Utils::isAdmin())
                <li class="border-bottom"></li>

                <li class="mb-2">
                    <a class="d-flex align-items-center text-dark text-decoration-none"
                        href="{{ route('createTaskView') }}">
                        <i class="fa-solid fa-angle-right px-1"></i> Add Task <i class="fa-solid fa-plus ps-2"></i>
                    </a>
                </li>
                <li class="mb-2">
                    <a class="d-flex align-items-center text-dark text-decoration-none"
                        href="{{ route('readUsers') }}">
                        <i class="fa-solid fa-angle-right px-1"></i> Users <i class="fa-solid fa-users-gear ps-2"></i>
                    </a>
                </li>
            @endif
            <li class="border-bottom"></li>
            <li class="my-4">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                        id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://github.com/mdo.png" alt="" width="32" height="32"
                            class="rounded-circle me-2">
                        <strong>{{ Utils::getNametoNav() }}</strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1"
                        style="">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}">Sign out</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    @endif
</div>
