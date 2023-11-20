<div class="p-4 bg-white mt-5" style="width: 280px;">
    <a href="/" class="d-flex align-items-center pb-3 mb-3 link-dark text-decoration-none border-bottom">
        <svg class="bi me-2" width="30" height="24">
            <use xlink:href="#bootstrap"></use>
        </svg>
        <span class="fs-5 fw-semibold">Bunglebuild</span>
    </a>
    <ul class="list-unstyled ps-0">
        <li class="mb-3">
            <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse"
                data-bs-target="#home-collapse" aria-expanded="false">
                <i class="bi bi-caret-down-fill"></i>
                Home
            </button>
            <div class="collapse" id="home-collapse" style="">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-2">
                    <li style="margin:0.7em;"><a href="{{ route('home') }}" class="link-dark rounded">Overview</a></li>
                    <li style="margin:0.7em;"><a href="#" class="link-dark rounded">Updates</a></li>
                    <li style="margin:0.7em;"><a href="#" class="link-dark rounded">Reports</a></li>
                </ul>
            </div>
        </li>
        <li class="mb-3">
            <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse"
                data-bs-target="#dashboard-collapse" aria-expanded="false">
                <i class="bi bi-caret-down-fill"></i>
                Dashboard
            </button>
            <div class="collapse" id="dashboard-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1">
                    <li style="margin:0.7em;"><a href="#" class="link-dark rounded">Overview</a></li>
                    <li style="margin:0.7em;"><a href="#" class="link-dark rounded">Weekly</a></li>
                    <li style="margin:0.7em;"><a href="#" class="link-dark rounded">Monthly</a></li>
                    <li style="margin:0.7em;"><a href="#" class="link-dark rounded">Annually</a></li>
                </ul>
            </div>
        </li>
        <li class="border-top my-3"></li>
        <li class="mb-3">
            <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse"
                data-bs-target="#account-collapse" aria-expanded="false">
                <i class="bi bi-person-badge-fill"></i>
                Account
            </button>
            <div class="collapse" id="account-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1">

                    @if (!isset($_SESSION['user_id']))
                        <li style="margin:0.7em;"><a href="{{ route('login') }}" class="link-dark rounded">Sign In</a>
                        </li>
                        <li style="margin:0.7em;"><a href="{{ route('register') }}" class="link-dark rounded">Sign
                                Up</a>
                        </li>
                    @else
                        <li style="margin:0.7em;"><a href="#" class="link-dark rounded">Settings</a></li>
                        <li style="margin:0.7em;"><a href="{{ route('logout') }}" class="link-dark rounded">Sign out</a>
                        </li>
                    @endif

                </ul>
            </div>
        </li>
    </ul>
</div>