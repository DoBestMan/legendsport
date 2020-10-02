<template>
    <div class="layout__navbar">
        <div class="logo" @click="goToHome">
            <img class="logo__header" src="assets/i/Logo.png" alt="Logo" />
        </div>

        <!-- if Authorized -->
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
                        <i class="icon icon--wallet icon--micro m--r--1"></i>
                        {{ user.balance | formatCurrency }}
                    </div>
                </div>

                <i
                    class="icon icon--small icon--menu d--only--desktop"
                    @click="displayAuthenticatedMenu"
                ></i>
                <i
                    class="icon icon--small icon--menu-mobile d--only--mobile icon--color--light-1"
                    @click="displayAuthenticatedMenu"
                ></i>
            </div>
        </div>

        <!-- if Unauthorized -->
        <div v-else class="layout__navbar__content">
            <button class="button--border button button--small m--r--2" @click="openSignInModal">
                LOG IN
            </button>
            <button class="button button--small button--yellow m--r--4" @click="openSignUpModal">
                JOIN
            </button>
            <i
                class="icon icon--small icon--menu d--only--desktop"
                @click="displayUnauthenticatedMenu()"
            ></i>
            <i
                class="icon icon--small icon--menu-mobile d--only--mobile icon--color--light-1"
                @click="displayUnauthenticatedMenu()"
            ></i>
        </div>

        <!-- Unauthenticated Menu -->
        <div v-show="isUnauthenticatedMenu" class="menu menu--active">
            <div class="menu__content">
                <div class="menu__content__header">
                    <div class="logo">
                        <div class="logo__icon d--only--mobile">LS</div>
                    </div>
                    <i
                        class="icon icon--close icon--color--light-1 icon--small"
                        @click="displayUnauthenticatedMenu()"
                    ></i>
                </div>
                <div class="menu__content__section">
                    <button
                        class="button button--large button--border m--b--4"
                        @click="openSignInModal"
                    >
                        LOG IN
                    </button>
                    <button
                        class="button button--large button--yellow m--b--0"
                        @click="openSignUpModal"
                    >
                        JOIN
                    </button>
                </div>
                <div class="seperator"></div>
                <div class="menu__content__section">
                    <div class="menu__content__section__item" @click="displayAboutUs">
                        About Us
                    </div>
                    <div class="menu__content__section__item" @click="displayContactUs">
                        Contact
                    </div>
                    <div class="menu__content__section__item">
                        Privacy
                    </div>
                    <div class="menu__content__section__item">
                        Terms of Use
                    </div>
                </div>
                <div class="seperator"></div>
                <div class="menu__content__section">
                    <div class="paragraph paragraph--small">
                        <span class="link">FOLLOW US ON</span>
                    </div>
                    <div class="menu__content__section__item">
                        <i class="icon icon--micro icon--color--light-1 icon--facebook m--r--2"></i>
                        Facebook
                    </div>
                    <div class="menu__content__section__item">
                        <i class="icon icon--micro icon--color--light-1 icon--twitter m--r--2"></i>
                        Twitter
                    </div>
                    <div class="menu__content__section__item">
                        <i
                            class="icon icon--micro icon--color--light-1 icon--instagram m--r--2"
                        ></i>
                        Instagram
                    </div>
                </div>
            </div>
        </div>

        <!-- Authenticated Menu-->
        <div v-show="isAuthenticatedMenu" class="menu menu--active">
            <div class="menu__content">
                <div class="menu__content__header">
                    <div class="logo">
                        <div class="logo__icon d--only--mobile">LS</div>
                    </div>
                    <i
                        class="icon icon--close icon--color--light-1 icon--small"
                        @click="displayAuthenticatedMenu"
                    ></i>
                </div>
                <div class="menu__content__section">
                    <div class="profile">
                        <div class="avatar">
                            <i class="icon icon--avatar icon--small"></i>
                        </div>
                        <div class="profile__content">
                            <div class="profile__content__name">
                                {{ isAuthenticated && user.name }}
                            </div>
                            <div class="profile__content__balance">
                                <i class="icon icon--wallet icon--micro m--r--1"></i>
                                {{ isAuthenticated && user.balance | formatCurrency }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="seperator"></div>
                <div class="menu__content__section">
                    <div class="menu__content__section__item" @click="displayProfilePage">
                        <i class="icon icon--micro icon--color--light-1 icon--person m--r--2"></i>
                        Profile
                    </div>
                    <div class="menu__content__section__item" @click="tournamentHistory">
                        <i class="icon icon--micro icon--color--light-1 icon--history m--r--2"></i>
                        History
                    </div>
                    <div class="menu__content__section__item" @click="displayCashierPage">
                        <i class="icon icon--micro icon--color--light-1 icon--coins m--r--2"></i>
                        Cashier
                    </div>
                    <div class="menu__content__section__item" @click="logout">
                        <i class="icon icon--micro icon--color--light-1 icon--logout m--r--2"></i>
                        Logout
                    </div>
                </div>
                <div class="seperator"></div>
                <div class="menu__content__section">
                    <div class="menu__content__section__item" @click="displayAboutUs">
                        About Us
                    </div>
                    <div class="menu__content__section__item" @click="displayContactUs">
                        Contact
                    </div>
                    <div class="menu__content__section__item">
                        Privacy
                    </div>
                    <div class="menu__content__section__item">
                        Terms of Use
                    </div>
                </div>
                <div class="seperator"></div>
                <div class="menu__content__section">
                    <div class="paragraph paragraph--small">
                        <span class="link">FOLLOW US ON</span>
                    </div>
                    <div class="menu__content__section__item">
                        <i class="icon icon--micro icon--color--light-1 icon--facebook m--r--2"></i>
                        Facebook
                    </div>
                    <div class="menu__content__section__item">
                        <i class="icon icon--micro icon--color--light-1 icon--twitter m--r--2"></i>
                        Twitter
                    </div>
                    <div class="menu__content__section__item">
                        <i
                            class="icon icon--micro icon--color--light-1 icon--instagram m--r--2"
                        ></i>
                        Instagram
                    </div>
                </div>
            </div>
        </div>
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

    data() {
        return {
            isAuthenticatedMenu: false,
            isUnauthenticatedMenu: false,
        };
    },

    computed: {
        user(): User | null {
            return this.$stock.state.user.user;
        },

        isAuthenticated(): boolean {
            return !!this.user;
        },
    },

    methods: {
        goToHome(): void {
            this.$router.push("/");
        },

        logout(): void {
            this.setDefaultMenuValues();
            this.$stock.dispatch("user/logout");
            this.$router.push("/");
        },

        tournamentHistory(): void {
            this.setDefaultMenuValues();
            this.$router.push("/history");
        },

        displayAboutUs(): void {
            this.setDefaultMenuValues();
            this.$router.push("/about");
        },

        displayContactUs(): void {
            this.setDefaultMenuValues();
            this.$router.push("/support");
        },

        displayCashierPage(): void {
            this.setDefaultMenuValues();
            this.$router.push("/cashier");
        },

        displayProfilePage(): void {
            this.setDefaultMenuValues();
            this.$router.push("/profile");
        },

        openSignUpModal(): void {
            this.setDefaultMenuValues();
            this.$stock.commit("authModal/open", AuthModalTab.SignUp);
            this.$router.push("/signup");
        },

        openSignInModal(): void {
            this.setDefaultMenuValues();
            this.$stock.commit("authModal/open", AuthModalTab.SignIn);
            this.$router.push("/login");
        },

        displayAuthenticatedMenu(): void {
            this.isAuthenticatedMenu = !this.isAuthenticatedMenu;
        },

        displayUnauthenticatedMenu(): void {
            this.isUnauthenticatedMenu = !this.isUnauthenticatedMenu;
        },

        setDefaultMenuValues(): void {
            this.isAuthenticatedMenu = false;
            this.isUnauthenticatedMenu = false;
        },
    },
});
</script>
