<template>
    <!-- <form @submit.prevent="signIn">
        <div class="form-group">
            <label for="form-email">E-mail</label>
            <FormInput
                id="form-email"
                inputClass="form-control form-control-lg"
                type="email"
                autocomplete="email"
                :errors="errors.email"
                v-model="email"
                required
            />
        </div>
        <div class="form-group">
            <label for="form-password">Password</label>
            <FormInput
                id="form-password"
                inputClass="form-control form-control-lg"
                type="password"
                autocomplete="current-password"
                :errors="errors.password"
                v-model="password"
                required
            />
        </div>
        <div class="form-group mt-5">
            <button type="submit" class="btn btn-action btn-block btn-lg">Sign In</button>
        </div>
    </form> -->

    <div class="layout">
        <div class="layout__navbar layout__navbar--transparent">
            <div class="logo">
                <div class="logo__icon">LS</div>
                <div class="logo__text d--only--desktop">LEGEND SPORTS</div>
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
                    <a href="#" class="link">Join Us</a>
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
        async signIn() {
            console.log("signin", this.email, this.password);
            try {
                await this.$stock.state.api.signIn({
                    email: this.email,
                    password: this.password,
                });
                this.$stock.dispatch("tournamentHistoryList/load");
                this.$emit("success");
            } catch (e) {
                console.log("error", e);
                this.$toast.error((e as AxiosError).response?.data.message);
                this.errors = (e as AxiosError).response?.data.errors ?? {};
            }
        },

        onSuccess(): void {
            this.$stock.dispatch("user/reload");
            this.$stock.commit("authModal/updateVisibility", false);
        },
    },
});
</script>
