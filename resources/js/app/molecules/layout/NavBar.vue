<template>
    <nav id="menu-frm" class="row">
        <div class="col-4">
            <router-link id="brand-frm" to="/">
                <div id="logo-text-frm" class="d-inline-blockx align-top">
                    <div id="logo-text" class="">LS</div>
                </div>
                <span id="text">Legend Sports</span>
            </router-link>
        </div>

        <div v-if="isAuthenticated" class="offset-5 col-3">
            <div id="usermenu-frm">
                <div id="img-frm">
                    <div id="img">
                        <i class="icon fas fa-user"></i>
                    </div>
                </div>

                <div id="title-frm">
                    <div id="title">
                        {{ user.name }}
                        <br />
                        <span class="balance">Bal: {{ user.balance | formatCurrency }}</span>
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

                    <a class="menu" @click="logout">
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

        <div v-else class="sign-frm offset-4 col-4">
            <a class="btn sign-up-btn center" href="/register">
                Sign up
            </a>
            <a class="btn sign-in-btn center" href="/login">
                Sign in
            </a>
        </div>
    </nav>
</template>

<script lang="ts">
import Vue from "vue";
import { User } from "../../../general/types/user";

export default Vue.extend({
    name: "NavBar",

    computed: {
        user(): User | null {
            return this.$stock.state.user.user;
        },

        isAuthenticated(): boolean {
            return !!this.user;
        },
    },

    methods: {
        logout(): void {
            this.$stock.dispatch("user/logout");
            this.$toast.info("You've been logged out.");
        },
    },
});
</script>
