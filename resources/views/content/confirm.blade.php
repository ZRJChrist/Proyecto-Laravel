@php
    $statusColors = [
        'B' => ['class' => 'text-bg-secondary', 'icon' => '<i class="fas fa-question"></i>'],
        'P' => ['class' => 'text-bg-warning', 'icon' => '<i class="fas fa-circle-pause"></i>'],
        'R' => ['class' => 'text-bg-success', 'icon' => '<i class="fas fa-check"></i>'],
        'C' => ['class' => 'text-bg-danger', 'icon' => '<i class="fas fa-ban"></i>'],
    ];
@endphp
@section('title', 'Tarea')
@extends('content.tasks')
@section('breadcrumb')
    <li class="breadcrumb-item text-info "><i class="fas fa-eye"></i> Ver Tarea</li>
    <li class="breadcrumb-item active" aria-current="page">{{ $task['task_id'] }}</li>
@endsection

@section('crud')
    <div class="alert alert-danger" role="alert">
        ¿Estas seguro que deseas eliminar esta tarea?
        <div class="p-1">
            <a class="btn btn-outline-danger" href='#' role="button">Eliminar</a>
        </div>

    </div>
    <div class="container mt-4 hover-shadow p-4">
        <h1><span class="font-weight-bold">- {{ $task['description'] }}</span></h1>
        <hr class="hr" />

        <div class="row">
            <div class="col-md-6">
                <div class=" px-3 border-0 rounded-3 list-group-item-dark p-2 mb-2">Contacto</div>

                <ul class="list-group list-group-light mb-4 mr-4">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="ms-3">
                                <p class="fw-bold mb-2">{{ $task['firstName'] }} {{ $task['lastName'] }}</p>
                                <p class="text-muted mb-1">Email: {{ $task['email'] }}</p>
                                <p class="text-muted mb-1">NIF/CIF: {{ $task['nif_cif'] }}</p>
                                <p class="text-muted mb-1">Phone: {{ $task['phoneNumber'] }}</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <div class=" px-3 border-0 rounded-3 list-group-item-dark p-2 mb-2">Tarea</div>
                <ul class="list-group list-group-light mb-4 mr-4">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="ms-3">
                                <p class="mb-2"> <strong>ID de Tarea:</strong> {{ $task['task_id'] }} </p>
                                <p class="text-muted mb-1">Provincia/Municipio: {{ $task['province_id'] }} -
                                    {{ $task['location'] }}</p>
                                <p class="text-muted mb-1">Direccion: {{ $task['direccion'] }}</p>
                                <p class="text-muted mb-1">CP: {{ $task['codigoPostal'] }}</p>
                                <p class="text-muted mb-1">Finalizacion: {{ $task['date_task'] }}</p>
                                <p class="text-muted mb-1">Creacion: {{ $task['date_creation'] }}</p>
                                <p class="text-muted mb-1">Operario: {{ $task['operario'] }}</p>
                                <p class="text-muted mb-1">Estado: @if (array_key_exists($task['status_task'], $statusColors))
                                        <span
                                            class="badge {{ $statusColors[$task['status_task']]['class'] }} rounded-pill d-inline p-1">
                                            {{ $task['statusDescription'] }} {!! $statusColors[$task['status_task']]['icon'] !!}
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="mx-5">
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Informacion Inicial
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            {{ $task['inf_task'] }}
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Informacion Adicional por Operarios
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            {{ $task['feedback_task'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
