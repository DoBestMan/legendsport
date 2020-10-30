<template>
    <div class="layout">
        <div class="layout__full">
            <div class="container">
                <div class="paging">
                    <div class="paging__item">
                        <i
                            class="icon icon--left icon--large icon--color--light-1 m--r--4"
                            @click="handleCancel"
                        ></i>
                        <div class="paging__item__title">Edit Profile</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="profile--card m--b--10">
                            <div
                                class="profile--card__holder profile--card__holder--padding-only-mobile"
                            >
                                <div
                                    class="profile--card__holder__image"
                                    style="background-image: url()"
                                >
                                    <div class="profile--card__holder__image__edit">
                                        <i class="icon icon--massive icon--camera"></i>
                                    </div>
                                    <div class="profile--card__holder__image__tag">
                                        PRO
                                    </div>
                                </div>
                            </div>
                            <div class="d--only--desktop">
                                <div class="profile--card__username m--b--0">
                                    {{ user && user.name }}
                                </div>
                                <div class="seperator seperator--large seperator--marginless"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-8">
                        <!-- Change Password Form -->
                        <form class="form" @submit.prevent="handleChangePassword">
                            <div class="section">
                                <div class="section__title">
                                    CHANGE PASSWORD
                                </div>
                            </div>
                            <div class="row m--b--6">
                                <div class="col-xs-12 col-md-4">
                                    <label class="label label--large">CURRENT PASSWORD</label>
                                    <div class="form__control m--b--4">
                                        <input
                                            class="input input--large"
                                            type="password"
                                            v-model="current_password"
                                        />
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4">
                                    <label class="label label--large">NEW PASSWORD</label>
                                    <div class="form__control m--b--4">
                                        <input
                                            class="input input--large"
                                            type="password"
                                            v-model="password"
                                        />
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4">
                                    <label class="label label--large">CONFIRM PASSWORD</label>
                                    <div class="form__control m--b--4">
                                        <input
                                            class="input input--large"
                                            type="password"
                                            v-model="password_confirmation"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="row center-xs m--b--10 d--only--desktop">
                                <button
                                    class="button button--large button--inline button--border"
                                    @click="handleCancel"
                                >
                                    CANCEL
                                </button>
                                <button type="submit" class="button button--large button--inline">
                                    SAVE
                                </button>
                            </div>
                            <div class="fixed d--only--mobile">
                                <button
                                    class="fixed__item"
                                    @click="handleCancel"
                                    style="border: none;"
                                >
                                    CANCEL
                                </button>
                                <button
                                    class="fixed__item fixed__item--green"
                                    type="submit"
                                    style="border: none;"
                                >
                                    SAVE
                                </button>
                            </div>
                            <div class="fixed--margin d--only--mobile"></div>
                        </form>

                        <!-- Change Email Form -->
                        <form class="form" @submit.prevent="handleChangeEmail">
                            <div class="section">
                                <div class="section__title">
                                    CHANGE EMAIL
                                </div>
                            </div>
                            <div class="row m--b--2">
                                <div class="col-xs-12 col-md-6">
                                    <label class="label label--large">CURRENT PASSWORD</label>
                                    <div class="form__control m--b--4">
                                        <input
                                            class="input input--large"
                                            type="password"
                                            v-model="currentPassword"
                                        />
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <label class="label label--large">NEW EMAIL</label>
                                    <div class="form__control m--b--4">
                                        <input class="input input--large" v-model="email" />
                                    </div>
                                </div>
                            </div>
                            <div class="row center-xs m--b--10 d--only--desktop">
                                <button
                                    class="button button--large button--inline button--border"
                                    @click="handleCancel"
                                >
                                    CANCEL
                                </button>
                                <button type="submit" class="button button--large button--inline">
                                    SAVE
                                </button>
                            </div>
                            <div class="fixed d--only--mobile">
                                <button
                                    class="fixed__item"
                                    @click="handleCancel"
                                    style="border: none;"
                                >
                                    CANCEL
                                </button>
                                <button
                                    class="fixed__item fixed__item--green"
                                    type="submit"
                                    style="border: none;"
                                >
                                    SAVE
                                </button>
                            </div>
                            <div class="fixed--margin d--only--mobile"></div>
                        </form>

                        <!-- Change Profile Form -->
                        <form class="form" @submit.prevent="handleChangeInformation">
                            <div class="section">
                                <div class="section__title">
                                    CHANGE PROFILE
                                </div>
                            </div>
                            <div class="row m--b--2">
                                <div class="col-xs-12 col-md-4">
                                    <label class="label label--large">USER NAME</label>
                                    <div class="form__control m--b--4">
                                        <input class="input input--large" v-model="userName" />
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4">
                                    <label class="label label--large">FIRST NAME</label>
                                    <div class="form__control m--b--4">
                                        <input class="input input--large" v-model="firstName" />
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4">
                                    <label class="label label--large">LAST NAME</label>
                                    <div class="form__control m--b--4">
                                        <input class="input input--large" v-model="lastName" />
                                    </div>
                                </div>
                            </div>
                            <div class="row center-xs m--b--10 d--only--desktop">
                                <button
                                    class="button button--large button--inline button--border"
                                    @click="handleCancel"
                                >
                                    CANCEL
                                </button>
                                <button type="submit" class="button button--large button--inline">
                                    SAVE
                                </button>
                            </div>
                            <div class="fixed d--only--mobile">
                                <button
                                    class="fixed__item"
                                    @click="handleCancel"
                                    style="border: none;"
                                >
                                    CANCEL
                                </button>
                                <button
                                    class="fixed__item fixed__item--green"
                                    type="submit"
                                    style="border: none;"
                                >
                                    SAVE
                                </button>
                            </div>
                            <div class="fixed--margin d--only--mobile"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import { AxiosError } from "axios";
import { User } from "../../../general/types/user";

export default Vue.extend({
    name: "ProfileEdit",

    data() {
        return {
            errors: {},
            current_password: "",
            password: "",
            password_confirmation: "",

            currentPassword: "",
            email: "",

            userName: "",
            firstName: "",
            lastName: "",
        };
    },

    computed: {
        user(): User | null {
            return this.$stock.state.user.user;
        },
    },

    methods: {
        handleCancel(): void {
            this.$router.push("/profile");
        },

        async handleChangePassword() {
            try {
                await this.$stock.state.api.changePassword({
                    current_password: this.current_password,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                });
                this.$stock.dispatch("user/reload");
                this.$router.push("/lobby");
            } catch (e) {
                this.$toast.error((e as AxiosError).response?.data.message);
                this.errors = (e as AxiosError).response?.data.errors ?? {};
            }
        },

        async handleChangeEmail() {
            try {
                await this.$stock.state.api.changeEmail({
                    current_password: this.currentPassword,
                    email: this.email,
                });
                this.$stock.dispatch("user/reload");
                this.$router.push("/lobby");
            } catch (e) {
                this.$toast.error((e as AxiosError).response?.data.message);
                this.errors = (e as AxiosError).response?.data.errors ?? {};
            }
        },

        async handleChangeInformation() {
            try {
                await this.$stock.state.api.changeProfile({
                    name: this.userName,
                    firstname: this.firstName,
                    lastname: this.lastName,
                });
                this.$stock.dispatch("user/reload");
                this.$router.push("/lobby");
            } catch (e) {
                this.$toast.error((e as AxiosError).response?.data.message);
                this.errors = (e as AxiosError).response?.data.errors ?? {};
            }
        },
    },
});
</script>
