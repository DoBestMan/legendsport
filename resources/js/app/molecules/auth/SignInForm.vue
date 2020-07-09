<template>
    <form @submit.prevent="signIn">
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
    </form>
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
            try {
                await this.$stock.state.api.signIn({
                    email: this.email,
                    password: this.password,
                });
                this.$stock.dispatch("tournamentHistoryList/load");
                this.$emit("success");
            } catch (e) {
                this.$toast.error((e as AxiosError).response?.data.message);
                this.errors = (e as AxiosError).response?.data.errors ?? {};
            }
        },
    },
});
</script>
