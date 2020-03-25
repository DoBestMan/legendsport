<template>
    <div class="event-frm">
        <div class="data-frm">
            <div class="trash" @click="remove">
                <i class="icon fas fa-trash-alt"></i>
            </div>

            <div class="text">{{ game.match_time | toDateTime }}</div>
            <div class="text game-frm">
                <div class="text team">{{ game.home_team }}</div>
                <div class="text score">0</div>
                <div class="text vs">vs</div>
                <div class="text team">{{ game.away_team }}</div>
                <div class="text score">0</div>
            </div>
            <div class="text">{{ team }} / {{ oddValue | formatOdd }}</div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { DeepReadonly } from "../../../general/types/types";
import { Game } from "../../types/game";
import { PendingOdd } from "../../types/window";
import { getPendingOddTeam, getPendingOddValue } from "../../utils/game/bet";
import { Odd } from "../../../general/types/odd";

export default Vue.extend({
    name: "ParlayItem",
    props: {
        game: Object as PropType<DeepReadonly<Game>>,
        pendingOdd: Object as PropType<DeepReadonly<PendingOdd>>,
    },

    computed: {
        odd(): Odd | null {
            const dictionary: ReadonlyMap<string, Odd> = this.$store.getters["odd/oddDictionary"];
            return dictionary.get(this.pendingOdd.eventId) ?? null;
        },

        team(): string {
            return getPendingOddTeam(this.pendingOdd, this.game);
        },

        oddValue(): number {
            return this.odd ? getPendingOddValue(this.pendingOdd, this.odd) : 0;
        },
    },

    methods: {
        remove() {
            this.$emit("delete");
        },
    },
});
</script>
