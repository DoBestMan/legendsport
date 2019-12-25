@php
    $routeHome = route('backstage.home');
    $routeTournaments = route('tournaments.index');
    $routeConfig = route('config.edit');
@endphp

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Backstage</title>

    <link rel="icon" href="_global/img/favicon.png" type="image/png">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/solid.css" integrity="sha384-doPVn+s3XZuxfJLS7K1E+sUl25XMZtTVb3O46RyV3JDU2ehfc0Aks4z0ufFpA2WC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/brands.css" integrity="sha384-tft2+pObMD7rYFMZlLUziw/8QrQeKHU4GYYvA5jVaggC74ZrYdTASheA2vckPcX5" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/fontawesome.css" integrity="sha384-+pqJl+lfXqeZZHwVRNTbv2+eicpo+1TR/AEzHYYDKfAits/WRK21xLOwzOxZzJEZ" crossorigin="anonymous">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('backstage/css/_global.css') }}">

    {{-- local --}}
    @yield('HTML-css')
</head>
<body>
    <div id="main" class="container-fluid">
        <nav name="menu" id="menu-frm" class="row">
            <div name="brand" class="col-4">
                <a id="brand-frm" href="{{ $routeHome }}">
                        <img id="logo" src="" class="d-inline-block align-top" alt="">
                    <div id="logo-text-frm" class="d-inline-blockx align-top">
                        <div id="logo-text" class="">LS</div>
                    </div>
                    <span id="text">backstage</span>
                </a>
            </div>
            
            <div class="offset-5 col-3">
                <a class="menu" href="{{ $routeConfig }}">
                    Config
                </a>
                
                <a class="menu" href="{{ $routeTournaments }}">
                    Tournaments
                </a>
            </div>
        </nav>
        
        @yield('HTML-main')

    </div>

    <script type="text/javascript" language="javascript" src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('backstage/js/_global.components.js') }}"></script>
    
    {{-- local --}}
    @yield('HTML-js')

</body>
</html>        

