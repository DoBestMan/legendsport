<template>
    <div class="dashboard-container">
        <header class="headermain">
            <div class="container">
                <div class="header-wraper">
                    <div class="logo">
                        <router-link to="/"><img src="assets/i/logo2.png "/></router-link>
                    </div>
                    <div class="navigations">
                        <ul class="nav desktopview">
                            <li><a class=" " href="#">How To Play </a></li>
                            <li><a class=" " href="#">Reviews</a></li>
                            <li><router-link to="/about">About</router-link></li>
                        </ul>

                        <div id="mySidenav" class="sidenav">
                            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">
                                &times;
                            </a>
                            <ul class="nav ">
                                <li><a class=" " href="#">How To Play</a></li>
                                <li><a class=" " href="#">Reviews</a></li>
                                <li><router-link to="/about">About</router-link></li>
                            </ul>
                            <div class="join-buton">
                                <router-link class="btn btn-outline" to="/signup">
                                    Join a Tournament
                                </router-link>
                                <router-link class="loginbtn btn-outline" to="/login">
                                    Login / Join
                                </router-link>
                            </div>
                        </div>
                    </div>

                    <div class="join-buton desktopview">
                        <router-link class="btn btn-outline" to="/signup">
                            Join a Tournament
                        </router-link>
                        <router-link class="loginbtn btn-outline" to="/login">
                            Login / Join
                        </router-link>
                    </div>
                    <span class="mobileview togglebtnmob" onclick="openNav()">&#9776;</span>
                </div>
            </div>
        </header>
    </div>
</template>

<script>
export default {
    name: "NewNavBar",
};
</script>

<style scoped></style>
