<template>
    <div v-if="isLoading" class="text-center p-5">
        <div class="spinner-wrapper">
            <div class="spinner-border">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>

    <NotFound v-else-if="!tab" />

    <TournamentContainer v-else :tab="tab" />
</template>

<script lang="ts">
import Vue from "vue";
import NotFound from "../components/NotFound.vue";
import { asNumber } from "../../general/utils/utils";
import { Tab } from "../types/tab";
import TournamentContainer from "../molecules/tournament/TournamentContainer.vue";
import { DeepReadonly } from "../../general/types/types";

export default Vue.extend({
    name: "TournamentView",
    components: { TournamentContainer, NotFound },

    computed: {
        tournamentId(): number | null {
            return asNumber(this.$route.params.tournamentId);
        },

        tab(): DeepReadonly<Tab> | null {
            return (
                this.$store.getters["tabs/tabs"].find((tab: Tab) => tab.id === this.tournamentId) ??
                null
            );
        },

        isLoading(): boolean {
            return this.$store.state.tournamentList.isLoading;
        },
    },

    watch: {
        tab(newVal: Tab | null, oldVal: Tab | null) {
            if (!newVal && oldVal) {
                this.$router.push(`/`);
            }
        },
    },
});
</script>
