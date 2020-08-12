<template>
    <!-- <form @submit.prevent="signUp">
        <div class="form-group">
            <label for="form-name">Name</label>
            <FormInput
                id="form-name"
                inputClass="form-control form-control-lg"
                type="text"
                autocomplete="username"
                :errors="errors.name"
                v-model="name"
                required
            />
        </div>
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
                minlength="8"
                required
            />
        </div>
        <div class="form-group">
            <label for="form-password-confirmation">Confirm Password</label>
            <FormInput
                id="form-password-confirmation"
                inputClass="form-control form-control-lg"
                type="password"
                autocomplete="current-password"
                :errors="errors.password_confirmation"
                v-model="passwordConfirmation"
                minlength="8"
                required
            />
        </div>
        <div class="form-group mt-5">
            <button type="submit" class="btn btn-action btn-block btn-lg">Sign Up</button>
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
                <h2 class="subtitle subtitle--light text--center">JOIN OUR COMMUNITY</h2>
                <form class="form" @submit.prevent="signUp">
                    <div class="form__control m--b--4">
                        <label class="label label--large">E-MAIL</label>
                        <FormInput
                            id="form-name"
                            inputClass="input input--large"
                            placeHolder="Ex. john@google.com"
                            type="text"
                            autocomplete="username"
                            :errors="errors.name"
                            v-model="name"
                            required
                        />
                    </div>
                    <div class="form__control m--b--4">
                        <label class="label label--large">Email</label>
                        <FormInput
                            id="form-email"
                            inputClass="input input--large"
                            placeHolder="Pick a email"
                            type="email"
                            autocomplete="email"
                            :errors="errors.email"
                            v-model="email"
                            required
                        />
                    </div>
                    <div class="form__control m--b--4">
                        <label class="label label--large">PASSWORD</label>
                        <FormInput
                            id="form-password"
                            inputClass="input input--large"
                            placeHolder="Minimum 8 characters"
                            type="password"
                            autocomplete="current-password"
                            :errors="errors.password"
                            v-model="password"
                            minlength="8"
                            required
                        />
                    </div>
                    <div class="form__control m--b--10">
                        <label class="label label--large">CONFIRM PASSWORD</label>
                        <FormInput
                            id="form-password-confirmation"
                            inputClass="input input--large"
                            placeHolder="Minimum 8 characters"
                            type="password"
                            autocomplete="current-password"
                            :errors="errors.password_confirmation"
                            v-model="passwordConfirmation"
                            minlength="8"
                            required
                        />
                    </div>
                    <button type="submit" class="button button--large m--b--6">JOIN</button>
                </form>
                <div class="paragraph paragraph--small">
                    Already have an account?
                    <a href="/login" class="link">Log in</a>
                </div>
                <div class="seperator"></div>
                <div class="paragraph paragraph--tiny">
                    By creating an account you are agreeing to Legend Sports’s
                    <a href="#" class="link">Terms of Use</a>
                    and
                    <a href="#" class="link">Privacy Policy</a>
                    and to be updated about Legend Sports products, news, and promotions.
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import { AxiosError } from "axios";
import FormInput from "../../components/FormInput.vue";

export default Vue.extend({
    name: "SignUpForm",
    components: { FormInput },

    data() {
        return {
            errors: {},
            name: "",
            email: "",
            password: "",
            passwordConfirmation: "",
        };
    },

    methods: {
        async signUp() {
            try {
                await this.$stock.state.api.signUp({
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.passwordConfirmation,
                });
                this.$emit("success");
            } catch (e) {
                this.$toast.error((e as AxiosError).response?.data.message);
                this.errors = (e as AxiosError).response?.data.errors ?? {};
            }
        },
    },
});
</script>
