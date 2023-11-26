@extends('index')

@section('breadcrumb')
    <li class="breadcrumb-item" aria-current="page">Update</li>
@endsection
@section('content')
    <div aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page">Tareas</li>
            @yield('breadcrumb')
        </ol>
    </div>
    @yield('crud')
@endsection
