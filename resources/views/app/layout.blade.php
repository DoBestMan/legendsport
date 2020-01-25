@php
    $routeHome = route('app.home');
    $routeTournament = route('app.tournament');
@endphp

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Legend Sports</title>

    <link rel="icon" href="_global/img/favicon.png" type="image/png">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/solid.css" integrity="sha384-doPVn+s3XZuxfJLS7K1E+sUl25XMZtTVb3O46RyV3JDU2ehfc0Aks4z0ufFpA2WC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/brands.css" integrity="sha384-tft2+pObMD7rYFMZlLUziw/8QrQeKHU4GYYvA5jVaggC74ZrYdTASheA2vckPcX5" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/fontawesome.css" integrity="sha384-+pqJl+lfXqeZZHwVRNTbv2+eicpo+1TR/AEzHYYDKfAits/WRK21xLOwzOxZzJEZ" crossorigin="anonymous">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('_global/css/global.css') }}">

    {{-- local --}}
    @yield('HTML-css')
</head>
<body>
    <div id="main" class="container-fluid">
        <nav name="menu" id="menu-frm" class="row">
            <div name="brand" class="col-4">
                <a id="brand-frm" href="#">
                    {{-- <img id="logo" src="" class="d-inline-block align-top" alt=""> --}}
                    <div id="logo-text-frm" class="d-inline-blockx align-top">
                        <div id="logo-text" class="">LS</div>
                    </div>
                    <span id="text">Legend Sports</span>
                </a>
            </div>

            <div name="usermenu" v-if="isLogin" class="offset-5 col-3">
                <div id="usermenu-frm">
                    <div id="img-frm">
                        <div id="img">
                            <i class="icon fas fa-user"></i>
                        </div>
                    </div>

                    <div id="title-frm">
                        <div id="title">
                            Michael Jarrod
                            <br>
                            <span class="balance">Bal: $3,000</span>
                        </div>
                    </div>

                    <div class="btnMenuFrm col-1">
                        <label class="iconFrm" for="btnMenuCheck">
                            <i class="icon fas fa-bars"></i>
                        </label>
                    </div>

                    <input type="checkbox" id="btnMenuCheck">
                    <div class="btnMenuSubmenu">
                        <a class="menu">
                            <div class="menuImg">
                                <i class="fas fa-user-circle"></i>
                            </div>

                            <div class="menuTxt">
                                profile
                            </div>
                        </a>

                        <a class="menu">
                            <div class="menuImg">
                                <i class="fas fa-history"></i>
                            </div>

                            <div class="menuTxt">
                                history(tournaments)
                            </div>
                        </a>

                        <a class="menu">
                            <div class="menuImg">
                                <i class="icon fas fa-user"></i>
                            </div>

                            <div class="menuTxt">
                                cashier
                            </div>
                        </a>

                        <a class="menu">
                            <div class="menuImg">
                                <i class="fas fa-sign-out-alt"></i>
                            </div>

                            <div class="menuTxt">
                                logout
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div name="sign-buttons" v-else id="sign-frm" class="offset-6 col-2">
                <button id="sign-up-btn" type="button" class="button">Sign up</button>
                <button id="sign-in-btn" type="button" class="button">Sign in</button>
            </div>
        </nav>

        <section name="tabs" class="row">
            <div class="col tabs-row-frm">
                <div id="tabs-frm">
                    <div name="home" class="tab-frm">
                        <button type="button"
                            class="tab @yield('homeActive')"
                            onclick="window.location='{{ $routeHome }}'"
                            >
                            <i class="icon fas fa-home"></i>
                            Home
                        </button>
                        <span class="separator">|</span>
                    </div>

                    <template v-for="(tab, i) in userTournamentsActive">
                        <div class="tab-frm">
                            <button type="button"
                                class="tab"
                                :class="@yield('tournamentActive1')"
                                @click="@yield('tournamentActive2')"
                            >@{{ tab }}</button>
                                <!-- onclick="window.location='{{ $routeTournament }}'" -->

                            <span class="separator">|</span>
                        </div>
                    </template>
                </div>
            </div>
        </section>

        @yield('HTML-main')

        <footer name="footer" id="footer-frm" class="row">
            <div id="advertising-frm" class="col-4">
                <div id="advertising-image"></div>
            </div>

            <div id="links-frm" class="offset-4 col-3">
                <div class="row">
                    <div name="aboutFrm" class="col-4">
                        <div class="links-title">About<span class="separator">|</span></div>

                        <div class="link-frm">
                            <a class="link" href="#">About us</a>
                        </div>

                        <div class="link-frm">
                            <a class="link" href="#">Privacy</a>
                        </div>

                        <div class="link-frm">
                            <a class="link" href="#">Terms of use</a>
                        </div>
                    </div>

                    <div name="supportFrm" class="col-4">
                        <div class="links-title">Support<span class="separator">|</span></div>

                        <div class="link-frm">
                            <a class="link" href="#">Contact us</a>
                        </div>

                        <div class="link-frm addMultiline">
                            <a class="link" href="#">Forgot password</a>
                        </div>
                    </div>

                    <div name="supportFrm" class="col-4">
                        <div class="links-title">Follow us</div>

                        <div class="link-frm">
                            <a class="link" href="#"><i class="icon fab fa-facebook-square"></i>Facebook</a>
                        </div>

                        <div class="link-frm">
                            <a class="link" href="#"><i class="icon fab fa-twitter-square"></i>Twitter</a>
                        </div>

                        <div class="link-frm">
                            <a class="link" href="#"><i class="icon fab fa-instagram"></i>Instagram</a>
                        </div>
                    </div>
                </div>
            </div>

            <div name="showFooterFrm" class="col-1">
                <button type="button" class="btn btn-secondary float-right"><i class="fas fa-angle-up"></i></button>
            </div>
        </footer>
    </div>

    <script type="text/javascript" language="javascript" src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    {{-- local --}}
    @yield('HTML-js')

</body>
</html>        

