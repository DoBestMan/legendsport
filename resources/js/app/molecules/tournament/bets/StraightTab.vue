<template>
    <div class="layout__content__sidebar__games">
        <StraightItem
            :key="`${pendingOdd.externalId}#${pendingOdd.type}`"
            :pendingOdd="pendingOdd"
            :game="gameDict.get(pendingOdd.externalId)"
            :value="pendingOdd.wager"
            @delete="removeOdd(pendingOdd)"
            @change="updateOdd(pendingOdd, $event)"
            v-for="pendingOdd in pendingOdds"
        />
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { PendingOdd, Window } from "../../../types/window";
import { DeepReadonly } from "../../../../general/types/types";
import StraightItem from "./StraightItem.vue";
import { Game } from "../../../types/game";
import { PendingOddPayload } from "../../../store/modules/window";

export default Vue.extend({
    name: "StraightTab",
    components: { StraightItem },

    props: {
        window: Object as PropType<Window>,
    },

    computed: {
        gameDict(): ReadonlyMap<string, Game> {
            return new Map(this.window.tournament.games.map(game => [game.externalId, game]));
        },

        pendingOdds(): PendingOdd[] {
            return this.window.pendingOdds.filter(pendingOdd =>
                this.gameDict.has(pendingOdd.externalId),
            );
        },
    },

    methods: {
        updateOdd(pendingOdd: DeepReadonly<PendingOdd>, value: number) {
            const payload: PendingOddPayload = {
                windowId: this.window.id,
                tournamentEventId: pendingOdd.tournamentEventId,
                externalId: pendingOdd.externalId,
                type: pendingOdd.type,
                wager: value,
            };
            this.$stock.commit("window/updateOdd", payload);
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
