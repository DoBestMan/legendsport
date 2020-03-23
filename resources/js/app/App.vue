<template>
    <div class="app container-fluid">
        <nav name="menu" id="menu-frm" class="row">
            <div name="brand" class="col-4">
                <router-link id="brand-frm" to="/">
                    <div id="logo-text-frm" class="d-inline-blockx align-top">
                        <div id="logo-text" class="">LS</div>
                    </div>
                    <span id="text">Legend Sports</span>
                </router-link>
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
                            <br />
                            <span class="balance">Bal: $3,000</span>
                        </div>
                    </div>

                    <div class="btnMenuFrm col-1">
                        <label class="iconFrm" for="btnMenuCheck">
                            <i class="icon fas fa-bars"></i>
                        </label>
                    </div>

                    <input type="checkbox" id="btnMenuCheck" />
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

        <section class="row">
            <HorizontallyScrollable class="col tabs-row-frm">
                <div class="tabs-frm">
                    <div class="tab-frm">
                        <router-link tag="button" type="button" class="tab" to="/" exact>
                            <i class="icon fas fa-home"></i>
                            Home
                        </router-link>
                        <span class="separator">|</span>
                    </div>

                    <div class="tab-frm" v-for="tab in tabs" :key="tab.id">
                        <router-link
                            tag="button"
                            type="button"
                            class="tab"
                            :to="`/tournaments/${tab.id}`"
                        >
                            {{ tab.tournament.name }}
                        </router-link>
                        <div class="delete" style="margin-left: -5px" @click="closeTab(tab)"></div>
                        <span class="separator">|</span>
                    </div>
                </div>
            </HorizontallyScrollable>
        </section>

        <router-view />

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
                            <a class="link" href="#"
                                ><i class="icon fab fa-facebook-square"></i>Facebook</a
                            >
                        </div>

                        <div class="link-frm">
                            <a class="link" href="#"
                                ><i class="icon fab fa-twitter-square"></i>Twitter</a
                            >
                        </div>

                        <div class="link-frm">
                            <a class="link" href="#"
                                ><i class="icon fab fa-instagram"></i>Instagram</a
                            >
                        </div>
                    </div>
                </div>
            </div>

            <div name="showFooterFrm" class="col-1">
                <button type="button" class="btn btn-secondary float-right">
                    <i class="fas fa-angle-up"></i>
                </button>
            </div>
        </footer>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import HorizontallyScrollable from "./components/HorizontallyScrollable.vue";
import { Tab } from "./types/tab";

export default Vue.extend({
    name: "App",
    components: { HorizontallyScrollable },

    data() {
        return {
            isLogin: true,
        };
    },

    computed: {
        tabs(): Tab[] {
            return Object.values(this.$store.getters["tabs/tabs"]);
        },
    },

    methods: {
        closeTab(tab: Tab): void {
            this.$store.commit("tabs/closeTab", tab.id);
        },
    },
});
</script>
