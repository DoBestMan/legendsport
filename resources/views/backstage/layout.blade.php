<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Backstage</title>

    <link rel="icon" href="{{ asset('_global/img/favicon.png') }}" type="image/png">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/solid.css" integrity="sha384-doPVn+s3XZuxfJLS7K1E+sUl25XMZtTVb3O46RyV3JDU2ehfc0Aks4z0ufFpA2WC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/brands.css" integrity="sha384-tft2+pObMD7rYFMZlLUziw/8QrQeKHU4GYYvA5jVaggC74ZrYdTASheA2vckPcX5" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/fontawesome.css" integrity="sha384-+pqJl+lfXqeZZHwVRNTbv2+eicpo+1TR/AEzHYYDKfAits/WRK21xLOwzOxZzJEZ" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ mix('/backstage/css/backstage.css') }}">
</head>
<body>
    <form id="logout-form" action="{{ route('backstage.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <nav id="nav" class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a id="brand-frm" href="{{ route('backstage.home') }}">
            <div id="logo-text-frm" class="d-inline-blockx align-top">
                <div id="logo-text" class="">LS</div>
            </div>
            <span id="text">backstage</span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="//{{ env('APP_URL_DOMAIN') }}">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admins.index') }}">Admins</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('book.active') }}">Book</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('config.edit') }}">Configuration</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tournaments.index') }}">Tournaments</a>
                </li>
                <b-nav-item-dropdown
                    id="my-nav-dropdown"
                    text="Users"
                    toggle-class="nav-link-custom"
                    right
                >
                    <b-dropdown-item href="{{ route('withdrawals.pending') }}">Withdrawals</b-dropdown-item>
                    <b-dropdown-item href="{{route('users.export')}}">Export</b-dropdown-item>
                </b-nav-item-dropdown>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('backstage.logout') }}"
                       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <div id="main" class="container-fluid">
        @yield('HTML-main')
        <toasts></toasts>
        <full-loader></full-loader>
    </div>

    <script type="text/javascript" src="{{ mix('/backstage/js/manifest.js') }}"></script>
    <script type="text/javascript" src="{{ mix('/backstage/js/vendor.js') }}"></script>
    <script type="text/javascript" src="{{ mix('/backstage/js/index.js') }}"></script>

    {{-- PHP TO JS --}}
    @include("_phpvars")

    {{-- Vendors --}}
    @yield('HTML-jsVendors')

    {{-- local --}}
    @yield('HTML-js')

</body>
</html>
