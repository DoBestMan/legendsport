<template>
    <div class="layout">
        <div class="layout__navbar layout__navbar--transparent">
            <div class="logo" @click="goToHome">
                <img class="logo__header" src="assets/i/Logo.png" alt="Logo" />
            </div>
        </div>
        <div class="layout__center">
            <div class="layout__center__wrapper">
                <h2 class="subtitle subtitle--light text--center">LOG IN TO YOUR ACCOUNT</h2>
                <form class="form" @submit.prevent="signIn">
                    <div class="form__control m--b--4">
                        <label class="label label--large">E-MAIL</label>
                        <FormInput
                            id="form-email"
                            inputClass="input input--large"
                            placeHolder="Ex. john@google.com"
                            type="email"
                            autocomplete="email"
                            :errors="errors.email"
                            v-model="email"
                            required
                        />
                    </div>
                    <div class="form__control m--b--10">
                        <label class="label label--large">PASSWORD</label>
                        <FormInput
                            id="form-password"
                            inputClass="input input--large"
                            placeHolder="Minimum 8 characters"
                            type="password"
                            autocomplete="current-password"
                            :errors="errors.password"
                            v-model="password"
                            required
                        />
                    </div>
                    <button type="submit" class="button button--large m--b--6">LOG IN</button>
                </form>
                <a class="link">Forgot Password?</a>
                <div class="seperator"></div>
                <div class="paragraph paragraph--small">
                    Don’t have an account?
                    <!-- <a href="/signup" class="link">Join Us</a>
                    <a href="/" class="link link--back">Back</a> -->
                    <router-link class="link" to="/signup">Join Us</router-link>
                    <router-link class="link link--back" to="/">Back</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import FormInput from "../../components/FormInput.vue";
import { AxiosError } from "axios";

export default Vue.extend({
    name: "SignInForm",
    components: { FormInput },

    data() {
        return {
            errors: {},
            email: "",
            password: "",
        };
    },

    methods: {
        goToHome(): void {
            this.$router.push("/");
        },

        async signIn() {
            try {
                await this.$stock.state.api.signIn({
                    email: this.email,
                    password: this.password,
                });
                this.$stock.dispatch("tournamentHistoryList/load");
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
