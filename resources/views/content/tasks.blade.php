@extends('index')

@section('breadcrumb')
    <li class="breadcrumb-item" aria-current="page">Update</li>
@endsection
@section('content')
    <div aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a
                    class="icon-link link-success link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
                    href="{{ route('listTask') }}"><i class="fa-solid fa-list-check"></i> Tareas</a>
            </li>
            @yield('breadcrumb')
        </ol>
    </div>
    @yield('crud')
@endsection
