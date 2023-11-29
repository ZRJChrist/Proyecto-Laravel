<div class="p-4 mt-5" style="width: 280px;">
    <a href="{{ route('listTask') }}"
        class="d-flex align-items-center pb-3 mb-3 link-dark text-decoration-none border-bottom">
        <svg class="bi me-2" width="30" height="24">
            <use xlink:href="#bootstrap"></use>
        </svg>
        <h1 class= "fw-semibold"><i class="bi bi-buildings-fill"></i> Bunglebuild</h1>
    </a>
    <ul class="list-unstyled ps-0">
        @if (isset($_SESSION['user_id']))
            <li class="mb-3">
                <button class="btn btn-toggle align-items-center rounded collapsed fs-6" data-bs-toggle="collapse"
                    data-bs-target="#dashboard-collapse" aria-expanded="false">
                    <i class="bi bi-caret-down-fill"></i>
                    Dashboard
                </button>
                <div class="collapse" id="dashboard-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1">
                        <li class="mb-3">
                            <button class="btn btn-toggle align-items-center rounded btn-dark collapsed mt-2"
                                data-bs-toggle="collapse" data-bs-target="#addtask-collapse" aria-expanded="false"
                                style="margin:0.7em">
                                <i class="bi bi-caret-down"></i>
                                Tareas
                            </button>
                            <div class="collapse" id="addtask-collapse">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1">
                                    <li style="margin:1em;"><i class="bi bi-caret-right"></i><a
                                            href="{{ route('listTask') }}" class="link-dark rounded">Ver Todas</a>
                                    </li>
                                    <li style="margin:1em;"><i class="bi bi-caret-right"></i><a href="#"
                                            class="link-dark rounded">Estado</a>
                                    </li>
                                    <li style="margin-top:1em; margin-left:1em;"><i class="bi bi-caret-right"></i><a
                                            href="#" class="link-dark rounded">Fecha</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li style="margin:0.7em;"><i class="bi bi-caret-right"></i><a href="{{ route('addTask') }}"
                                class="link-dark rounded">Add Task</a></li>
                    </ul>
                </div>
            </li>
        @endif
        <li class="border-top my-3"></li>
        <li class="mb-3">
            <button class="btn btn-toggle align-items-center rounded collapsed fs-6" data-bs-toggle="collapse"
                data-bs-target="#account-collapse" aria-expanded="false">
                <i class="bi bi-person-badge-fill"></i>
                Account
            </button>
            <div class="collapse" id="account-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1">

                    @if (!isset($_SESSION['user_id']))
                        <li style="margin:0.7em;"><i class="bi bi-caret-right"></i><a href="{{ route('login') }}"
                                class="link-dark rounded">Sign
                                In</a>
                        </li>
                        <li style="margin:0.7em;"><i class="bi bi-caret-right"></i><a href="{{ route('register') }}"
                                class="link-dark rounded">Sign
                                Up</a>
                        </li>
                    @else
                        <li style="margin:0.7em;"><i class="bi bi-caret-right"></i><a href="#"
                                class="link-dark rounded">Settings</a></li>
                        <li style="margin:0.7em;"><i class="bi bi-caret-right"></i><a href="{{ route('logout') }}"
                                class="link-dark rounded">Sign
                                out</a>
                        </li>
                    @endif

                </ul>
            </div>
        </li>
    </ul>
</div>
