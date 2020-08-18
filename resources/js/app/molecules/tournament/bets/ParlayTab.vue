<template>
    <div class="layout__content__sidebar__games">
        <ParlayItem
            :key="`${pendingOdd.externalId}#${pendingOdd.type}`"
            :pendingOdd="pendingOdd"
            :game="getGame(pendingOdd.externalId)"
            :value="pendingOdd.wager"
            @delete="removeOdd(pendingOdd)"
            v-for="pendingOdd in pendingOdds"
        />
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import ParlayItem from "./ParlayItem.vue";
import { DeepReadonly } from "../../../../general/types/types";
import { PendingOdd, Window } from "../../../types/window";
import { PendingOddPayload } from "../../../store/modules/window";
import { Game } from "../../../types/game";
import { Tournament } from "../../../types/tournament";

export default Vue.extend({
    name: "ParlayTab",
    components: { ParlayItem },

    props: {
        window: Object as PropType<Window>,
    },

    computed: {
        tournament(): Tournament {
            return this.window.tournament;
        },

        pendingOdds(): PendingOdd[] {
            return this.window.pendingOdds;
        },
    },

    methods: {
        getGame(eventId: string): Game | null {
            return this.tournament.games.find(game => game.externalId === eventId) ?? null;
        },

        removeOdd(pendingOdd: DeepReadonly<PendingOdd>) {
            const payload: PendingOddPayload = {
                windowId: this.window.id,
                ...pendingOdd,
            };
            this.$stock.dispatch("window/toggleOdd", payload);
        },
    },
});
</script>
