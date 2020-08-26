<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Legend Sports</title>

    <link rel="icon" href="_global/img/favicon.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/solid.css" integrity="sha384-doPVn+s3XZuxfJLS7K1E+sUl25XMZtTVb3O46RyV3JDU2ehfc0Aks4z0ufFpA2WC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/brands.css" integrity="sha384-tft2+pObMD7rYFMZlLUziw/8QrQeKHU4GYYvA5jVaggC74ZrYdTASheA2vckPcX5" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/fontawesome.css" integrity="sha384-+pqJl+lfXqeZZHwVRNTbv2+eicpo+1TR/AEzHYYDKfAits/WRK21xLOwzOxZzJEZ" crossorigin="anonymous">
    <link rel="stylesheet" href="/app/css/app.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div id="main" class="cloak container-fluid">
        <nav id="menu-frm" class="row">
            <div class="col-4">
                <a id="brand-frm" href="#">
                    <div id="logo-text-frm" class="d-inline-blockx align-top">
                        <div id="logo-text" class="">LS</div>
                    </div>
                    <span id="text">Legend Sports</span>
                </a>
            </div>
        </nav>

        <div class="spinner-wrapper">
            <div class="spinner-border">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <script>
        echo = {
            "port": {{ config('broadcasting.client.port') }},
            "key": "{{ config('broadcasting.client.key') }}"
        }
    </script>
    <script type="text/javascript" src="{{ mix('/backstage/js/manifest.js') }}"></script>
    <script type="text/javascript" src="{{ mix('/backstage/js/vendor.js') }}"></script>
    <script type="text/javascript" src="{{ mix('/app/js/app.js') }}"></script>
</body>
</html>
