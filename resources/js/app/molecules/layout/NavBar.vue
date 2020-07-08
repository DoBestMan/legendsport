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

                    <a class="menu" @click="tournamentHistory">
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
            <button class="btn sign-up-btn center" @click="openSignUpModal">
                Sign up
            </button>
            <button class="btn sign-in-btn center" @click="openSignInModal">
                Sign in
            </button>
        </div>
    </nav>
</template>

<script lang="ts">
import Vue from "vue";
import { User } from "../../../general/types/user";
import ModalAuth from "../auth/ModalAuth.vue";
import { AuthModalTab } from "../../store/modules/authModal";

export default Vue.extend({
    name: "NavBar",
    components: { ModalAuth },

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
        },

        tournamentHistory(): void {
            this.$router.push("/history");
        },

        openSignUpModal(): void {
            this.$stock.commit("authModal/open", AuthModalTab.SignUp);
        },

        openSignInModal(): void {
            this.$stock.commit("authModal/open", AuthModalTab.SignIn);
        },
    },
});
</script>
