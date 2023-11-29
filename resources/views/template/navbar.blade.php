@php
    use App\Helpers\Utils;
@endphp
<div class="p-4" style="width: 280px;">
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
    @if (Utils::isLogIn())
        <button type="button" class="btn btn-primary">Primary</button>
    @endif
</div>
