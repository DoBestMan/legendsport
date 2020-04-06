<template>
    <section class="tab-content-frm tab-tournament-frm row">
        <BetsSection :window="window" />
        <MatchesSection :window="window" />
        <InfoSection :window="window" />
    </section>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { Window } from "../../types/window";
import BetsSection from "./bets/BetsSection.vue";
import InfoSection from "./info/InfoSection.vue";
import MatchesSection from "./matches/MatchesSection.vue";

export default Vue.extend({
    name: "TournamentContainer",
    components: {
        BetsSection,
        InfoSection,
        MatchesSection,
    },

    props: {
        window: Object as PropType<Window>,
    },
});
</script>
