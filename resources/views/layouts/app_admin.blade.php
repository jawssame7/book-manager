<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>書籍管理 - @yield('title')</title>

    <link href="{{ asset('css/lib/semantic.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="{{ asset('js/lib/jquery-3.6.0.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('js/lib/semantic.js') }}" ></script>
</head>
<body>
<div class="ui fixed inverted menu admin-menu">
    <div class="ui container">
        <a href="{{route('books.index')}}" class="header item title">
            <span class="menu-main-title">Book-Manager Admin</span>
        </a>
        <div class="ui secondary menu">
            <a class="item @isset($active_book){{ $active_book }}@endisset" href="{{route('books.index')}}">書籍管理</a>
            <a class="item @isset($active_place){{ $active_place }}@endisset" href="{{route('places.index')}}">保管場所管理</a>
            <a class="item @isset($active_employee){{ $active_employee }}@endisset" href="{{route('employee.index')}}">使用者管理</a>
        </div>
        <div class="right menu">
            {{-- <a class="ui item">
                Logout
            </a> --}}
        </div>
    </div>
</div>
<div class="content ui container main-content">
    @yield('content')
</div>
<script type="text/javascript" src="{{ asset('js/admin.js') }}" ></script>
</body>
</html>
