<template>
    <button class="btn btn-action btn-register" @click="register">Register now {{ price }}</button>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { AxiosError } from "axios";
import { Tournament } from "../types/tournament";
import { formatDollars } from "../utils/game/bet";

export default Vue.extend({
    name: "RegisterNowButton",

    props: {
        tournament: Object as PropType<Tournament>,
    },

    computed: {
        isAuthorized(): boolean {
            return !!this.$stock.state.user.user;
        },

        price(): string {
            return `${formatDollars(this.tournament.buyIn)}+${formatDollars(
                this.tournament.commission,
            )}`;
        },

        loginUrl(): string {
            return `/login?redirect_url=${encodeURIComponent(this.$route.path)}`;
        },
    },

    methods: {
        async register() {
            if (!this.isAuthorized) {
                window.location.href = this.loginUrl;
                return;
            }

            if (
                !confirm(
                    `Do you want to register at tournament '${this.tournament.name}' for ${this.price}?`,
                )
            ) {
                return;
            }

            this.$stock.commit("loader/show");

            try {
                await this.$stock.state.api.registerForTournament(this.tournament.id);
                this.$toast.info("You've registered for a tournament.");
            } catch (e) {
                this.$toast.error((e as AxiosError).response?.data.message);
            } finally {
                this.$stock.commit("loader/hide");
            }

            await this.$stock.dispatch("user/reload");
        },
    },
});
</script>
