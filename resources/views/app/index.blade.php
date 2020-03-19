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
    <link rel="stylesheet" href="{{ mix('/app/css/app.css') }}">
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

        <app></app>

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

    {{-- PHP TO JS --}}
    @include("_phpvars")

    <script type="text/javascript" src="{{ mix('/backstage/js/manifest.js') }}"></script>
    <script type="text/javascript" src="{{ mix('/backstage/js/vendor.js') }}"></script>
    <script type="text/javascript" src="{{ mix('/app/js/app.js') }}"></script>
</body>
</html>

