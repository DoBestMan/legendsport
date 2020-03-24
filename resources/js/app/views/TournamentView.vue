<template>
    <div v-if="isLoading" class="text-center p-5">
        <div class="spinner-wrapper">
            <div class="spinner-border">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>

    <NotFound v-else-if="!window" />

    <TournamentContainer v-else :window="window" />
</template>

<script lang="ts">
import Vue from "vue";
import NotFound from "../components/NotFound.vue";
import { asNumber } from "../../general/utils/utils";
import { Window } from "../types/window";
import TournamentContainer from "../molecules/tournament/TournamentContainer.vue";
import { DeepReadonly } from "../../general/types/types";

export default Vue.extend({
    name: "TournamentView",
    components: { TournamentContainer, NotFound },

    computed: {
        tournamentId(): number | null {
            return asNumber(this.$route.params.tournamentId);
        },

        window(): DeepReadonly<Window> | null {
            return (
                this.$store.getters["window/windows"].find(
                    (window: Window) => window.id === this.tournamentId,
                ) ?? null
            );
        },

        isLoading(): boolean {
            return this.$store.state.tournamentList.isLoading;
        },
    },

    watch: {
        window(newVal: Window | null, oldVal: Window | null) {
            if (!newVal && oldVal) {
                this.$router.push(`/`);
            }
        },
    },
});
</script>
