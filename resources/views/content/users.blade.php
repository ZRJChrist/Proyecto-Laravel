@extends('index')

@section('content')
    <div aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a
                    class="icon-link link-success link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
                    href="{{ route('readUsers') }}"><i class="fa-solid fa-users-gear"></i> Users</a>
            </li>
            @yield('breadcrumb')
        </ol>
    </div>
    @yield('crudUsers')
@endsection
