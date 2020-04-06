<template>
    <div class="footer-frm">
        <div v-if="!isAuthenticated" class="sign-frm">
            <a class="btn sign-up-btn button-place-bet" :href="registerUrl">
                Sign Up
            </a>
            <a class="btn sign-in-btn button-place-bet" :href="loginUrl">
                Sign In
            </a>
        </div>

        <button
            v-else-if="!isRegistered"
            class="btn button-place-bet button-action"
            @click="placeBet"
            :disabled="disabled"
        >
            Register now
        </button>

        <button
            v-else
            class="btn button-place-bet button-action"
            @click="placeBet"
            :disabled="disabled"
        >
            Place Bet
        </button>
    </div>
</template>

<script lang="ts">
import Vue from "vue";

export default Vue.extend({
    name: "PlaceBetButton",

    props: {
        disabled: Boolean,
        tournamentId: Number,
    },

    computed: {
        isAuthenticated(): boolean {
            return !!this.$stock.state.user.user;
        },

        isRegistered(): boolean {
            return !!this.$stock.state.user.user?.players.find(
                player => player.tournamentId === this.tournamentId,
            );
        },

        registerUrl(): string {
            return `/register?redirect_url=${encodeURIComponent(this.$route.path)}`;
        },

        loginUrl(): string {
            return `/login?redirect_url=${encodeURIComponent(this.$route.path)}`;
        },
    },

    methods: {
        placeBet() {
            this.$emit("placeBet");
        },
    },
});
</script>
