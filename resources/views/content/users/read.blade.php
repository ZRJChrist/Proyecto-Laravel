@php
    $roleColors = [
        '1' => ['class' => 'text-bg-success', 'icon' => '<i class="fa-solid fa-user-gear"></i>'],
        '0' => ['class' => 'text-bg-info', 'icon' => '<i class="fa-solid fa-user-large"></i>'],
    ];
@endphp
@extends('content.users')

@section('title', 'View Users')
@section('breadcrumb')
    <li class="breadcrumb-item" aria-current="page"> {{ $page }}</li>
@endsection
@section('crudUsers')
    <div aria-label="...">
        <ul class="pagination">
            <li class="page-item {{ $page - 1 == 0 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ route('readUsers', Utils::paramLinks($page - 1, $params)) }}">Previous</a>
            </li>

            @for ($i = $page - 1 == 0 ? 1 : $page - 1; $i <= ($page >= $total ? $page : $page + 1); $i++)
                <li class="page-item {{ $i == $page ? 'active' : '' }}">
                    <a class="page-link"
                        href="{{ route('readUsers', Utils::paramLinks($i, $params)) }}">{{ $i }}</a>
                </li>
            @endfor

            <li class="page-item {{ $page == $total ? 'disabled' : '' }}">
                <a class="page-link" href="{{ route('readUsers', Utils::paramLinks($page + 1, $params)) }}">Next</a>
            </li>
        </ul>
    </div>
    <div class="p-1 position-absolute top-0 " style="right:5%">
        <a class="btn btn-outline-info" href="{{ route('createUsersView') }}" role="button">Añadir Usuario
        </a>
    </div>
    <table class="table align-middle mb-1 table-hover rounded-3 hover-shadow" style="width: 95%">
        <thead class="bg-light">
            <tr>
                <th>Id</th>
                <th>User</th>
                <th>Role</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($users as $user)
                <tr>
                    <td>
                        <p class="fw-bold mb-1">{{ $user['id'] }}</p>
                    </td>
                    <td class="w-25">
                        <div class="d-flex align-items-center">
                            <div class="ms-3">
                                <p class="fw-bold mb-1">{{ $user['name'] }} {{ $user['last_name'] }}</p>
                                <p class="text-muted mb-0">Email: {{ $user['email'] }}</p>
                                <p class="text-muted mb-0">Phone: {{ $user['phoneNumber'] }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if (array_key_exists($user['role'], $roleColors))
                            <span class="badge {{ $roleColors[$user['role']]['class'] }} rounded-pill d-inline p-2">
                                {{ $user['roleDescription'] }} {!! $roleColors[$user['role']]['icon'] !!}
                            </span>
                        @endif
                    </td>
                    <td>
                        <p class="fw-normal mb-1">Actualizacion: {{ $user['updated_at'] }}</p>
                        <p class="text-muted mb-0">Creacion: {{ $user['created_at'] }}</p>
                    </td>
                    <td>
                        <div class="d-flex flex-column w-25">
                            <div class="d-flex flex-row">
                                <div class="p-1">
                                    <a class="btn btn-outline-warning btn-rounded"
                                        href="{{ route('editUser', ['id' => $user['id']]) }}" role="button">Editar</a>
                                </div>
                            </div>
                            <div class="p-1">
                                <a class="btn btn-outline-danger btn-rounded" href="#" role="button">Eliminar
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div aria-label="...">
        <ul class="pagination">
            <li class="page-item {{ $page - 1 == 0 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ route('readUsers', Utils::paramLinks($page - 1, $params)) }}">Previous</a>
            </li>

            @for ($i = $page - 1 == 0 ? 1 : $page - 1; $i <= ($page >= $total ? $page : $page + 1); $i++)
                <li class="page-item {{ $i == $page ? 'active' : '' }}">
                    <a class="page-link"
                        href="{{ route('readUsers', Utils::paramLinks($i, $params)) }}">{{ $i }}</a>
                </li>
            @endfor

            <li class="page-item {{ $page == $total ? 'disabled' : '' }}">
                <a class="page-link" href="{{ route('readUsers', Utils::paramLinks($page + 1, $params)) }}">Next</a>
            </li>
        </ul>
    </div>
@endsection
