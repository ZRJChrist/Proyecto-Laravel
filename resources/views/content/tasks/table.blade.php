@php
    $statusColors = [
        'B' => ['class' => 'text-bg-secondary', 'icon' => '<i class="fa-solid fa-hourglass-start"></i>'],
        'P' => ['class' => 'text-bg-warning', 'icon' => '<i class="fas fa-circle-pause"></i>'],
        'R' => ['class' => 'text-bg-success', 'icon' => '<i class="fas fa-check"></i>'],
        'C' => ['class' => 'text-bg-danger', 'icon' => '<i class="fas fa-ban"></i>'],
    ];
@endphp
@section('title', 'Tasks')
@extends('content.tasks')
@section('breadcrumb')
    @if (null != $params)
        <li class="breadcrumb-item"> {{ ucwords(key($params)) }} <i class="fa-solid fa-caret-right px-2"></i>
            {{ $params[key($params)] }}
        </li>
    @endif
    <li class="breadcrumb-item" aria-current="page"> {{ $page }}</li>
@endsection

@section('crud')
    <div aria-label="...">
        <ul class="pagination">
            <li class="page-item {{ $page - 1 == 0 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ route('readTasks', Utils::paramLinks($page - 1, $params)) }}">Previous</a>
            </li>

            @for ($i = $page - 1 == 0 ? 1 : $page - 1; $i <= ($page >= $total ? $page : $page + 1); $i++)
                <li class="page-item {{ $i == $page ? 'active' : '' }}">
                    <a class="page-link"
                        href="{{ route('readTasks', Utils::paramLinks($i, $params)) }}">{{ $i }}</a>
                </li>
            @endfor

            <li class="page-item {{ $page == $total ? 'disabled' : '' }}">
                <a class="page-link" href="{{ route('readTasks', Utils::paramLinks($page + 1, $params)) }}">Next</a>
            </li>
        </ul>
    </div>
    <table class="table align-middle mb-1 table-hover rounded-3 hover-shadow" style="width: 95%">
        <thead class="bg-light">
            <tr>
                <th>Contact</th>
                <th>Title</th>
                <th>Status</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
                <tr>
                    <td class="w-25">
                        <div class="d-flex align-items-center">
                            <div class="ms-3">
                                <p class="fw-bold mb-1">{{ $task['firstName'] }} {{ $task['lastName'] }}</p>
                                <p class="text-muted mb-0">Email: {{ $task['email'] }}</p>
                                <p class="text-muted mb-0">Phone: {{ $task['phoneNumber'] }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="w-25">
                        <p class="fw-normal mb-1">{{ $task['description'] }}</p>
                        <p class="text-muted mb-0">Fecha: {{ $task['date_task'] }}</p>
                        <div class="overflow-y-auto" style="height: 50px">
                            <p class="text-muted mb-0">Inf: {{ $task['inf_task'] }}</p>
                        </div>
                    </td>
                    <td>
                        @if (array_key_exists($task['status_task'], $statusColors))
                            <span
                                class="badge {{ $statusColors[$task['status_task']]['class'] }} rounded-pill d-inline p-2">
                                {{ $task['statusDescription'] }} {!! $statusColors[$task['status_task']]['icon'] !!}
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="ms-0">
                            <p class="fw-normal mb-1">{{ $task['province_id'] }}, {{ $task['location'] }}</p>
                            <p class="text-muted mb-0">Dir: {{ $task['direccion'] }}</p>
                            <p class="text-muted mb-0">CP: {{ $task['codigoPostal'] }}</p>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column w-25">
                            <div class="d-flex flex-row">
                                @if (Utils::isUserAuthorized($task['operario']))
                                    <div class="p-1">
                                        <a class="btn btn-outline-warning btn-rounded"
                                            href="{{ route('updateTask', ['id' => $task['task_id']]) }}"
                                            role="button">Editar</a>
                                    </div>
                                @endif

                                <div class="p-1">
                                    <a class="btn btn-outline-dark btn-rounded"
                                        href="{{ route('showTask', ['id' => $task['task_id']]) }}" role="button">Ver</a>
                                </div>
                            </div>
                            @if (Utils::isAdmin())
                                <div class="p-1">
                                    <a class="btn btn-outline-danger btn-rounded"
                                        href="{{ route('confirmDeletTask', ['id' => $task['task_id']]) }}"
                                        role="button">Eliminar
                                    </a>
                                </div>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div aria-label="...">
        <ul class="pagination">
            <li class="page-item {{ $page - 1 == 0 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ route('readTasks', Utils::paramLinks($page - 1, $params)) }}">Previous</a>
            </li>

            @for ($i = $page - 1 == 0 ? 1 : $page - 1; $i <= ($page >= $total ? $page : $page + 1); $i++)
                <li class="page-item {{ $i == $page ? 'active' : '' }}">
                    <a class="page-link"
                        href="{{ route('readTasks', Utils::paramLinks($i, $params)) }}">{{ $i }}</a>
                </li>
            @endfor

            <li class="page-item {{ $page == $total ? 'disabled' : '' }}">
                <a class="page-link" href="{{ route('readTasks', Utils::paramLinks($page + 1, $params)) }}">Next</a>
            </li>
        </ul>
    </div>
@endsection
