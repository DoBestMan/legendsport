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
    <div id="main" class="container-fluid">
        <nav id="menu-frm" class="row">
            <div class="col-4">
                <a id="brand-frm" href="{{ route('backstage.home') }}">
                    <div id="logo-text-frm" class="d-inline-blockx align-top">
                        <div id="logo-text" class="">LS</div>
                    </div>
                    <span id="text">backstage</span>
                </a>
            </div>

            <div class="offset-5 col-3">
                <a class="menu" href="//{{ env('APP_URL_DOMAIN') }}">Home</a>
                <label class="menu">|</label>
                <a class="menu" href="{{ route('tournaments.index') }}">Tournaments</a>
                <label class="menu">|</label>
                <a class="menu" href="{{ route('admins.index') }}">Admins</a>
                <label class="menu">|</label>
                <a class="menu" href="{{ route('config.edit') }}">Configuration</a>
                <label class="menu">|</label>
                <a class="menu" href="{{ route('backstage.logout') }}"
                   onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
            </div>

            <form id="logout-form" action="{{ route('backstage.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>

        @yield('HTML-main')
        <toasts></toasts>
        <full-loader></full-loader>
    </div>

    <script type="text/javascript" src="{{ mix('/backstage/js/manifest.js') }}"></script>
    <script type="text/javascript" src="{{ mix('/backstage/js/vendor.js') }}"></script>

    {{-- PHP TO JS --}}
    @include("_phpvars")

    {{-- Vendors --}}
    @yield('HTML-jsVendors')

    {{-- local --}}
    @yield('HTML-js')

</body>
</html>
