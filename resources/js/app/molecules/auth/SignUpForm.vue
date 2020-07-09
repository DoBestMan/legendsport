<template>
    <form @submit.prevent="signUp">
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
    </form>
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
