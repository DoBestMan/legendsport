<template>
    <div class="layout__navbar">
        <div class="logo" to="/">
            <div class="logo__icon">LS</div>
            <span class="logo__text d--only--desktop">Legend Sports</span>
        </div>

        <div v-if="isAuthenticated" class="layout__navbar__content">
            <div class="profile">
                <div class="avatar">
                    <i class="icon icon--avatar icon--small"></i>
                </div>

                <div class="profile__content">
                    <div class="profile__content__name">
                        {{ user.name }}
                    </div>
                    <div class="profile__content__balance">
                        Bal: {{ user.balance | formatCurrency }}
                    </div>
                </div>

                <!-- Todo -->
                <!-- <div class="btnMenuFrm col-1">
                    <label class="iconFrm" for="btnMenuCheck">
                        <i class="icon fas fa-bars"></i>
                    </label>
                </div> -->

                <i class="icon icon--small icon--menu d--only--desktop"></i>
                <i
                    class="icon icon--small icon--menu-mobile d--only--mobile icon--color--light-1"
                ></i>

                <!-- Todo -->
                <!-- <input type="checkbox" id="btnMenuCheck" />
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
                </div> -->
            </div>
        </div>

        <!-- Todo -->
        <!-- <div v-else class="sign-frm offset-4 col-4">
            <button class="btn sign-up-btn center" @click="openSignUpModal">
                Sign up
            </button>
            <button class="btn sign-in-btn center" @click="openSignInModal">
                Sign in
            </button>
        </div> -->
    </div>
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
