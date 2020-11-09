<template>
    <section class="layout__content">
        <InfoSection :window="window" />
        <MatchesSection :window="window" v-if="!showPlayerBetSection" />
        <PlayerBetInfo :window="window" v-if="showPlayerBetSection" />
        <BetsSection :window="window" />
    </section>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { Window } from "../../types/window";
import BetsSection from "./bets/BetsSection.vue";
import InfoSection from "./info/InfoSection.vue";
import MatchesSection from "./matches/MatchesSection.vue";
import PlayerBetInfo from "./info/PlayerBetInfo.vue";

export default Vue.extend({
    name: "TournamentContainer",
    components: {
        BetsSection,
        InfoSection,
        MatchesSection,
        PlayerBetInfo,
    },

    props: {
        window: Object as PropType<Window>,
    },
    computed: {
        showPlayerBetSection(): boolean {
            const tournamentId = this.$stock.getters["playerBetInfo/getTournamentId"];
            return this.window.tournament.id == tournamentId;
        },
    },
});
</script>
