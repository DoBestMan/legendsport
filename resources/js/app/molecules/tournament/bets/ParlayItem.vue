<template>
    <div class="event-frm">
        <div class="data-frm">
            <div class="trash" @click="remove">
                <i class="icon fas fa-trash-alt"></i>
            </div>

            <BetContent
                :matchTime="game.match_time"
                :homeTeam="game.home_team"
                :awayTeam="game.away_team"
                :selectedTeam="team"
                :odd="oddValue"
            />
        </div>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { DeepReadonly } from "../../../../general/types/types";
import { Game } from "../../../types/game";
import { PendingOdd } from "../../../types/window";
import { getPendingOddTeam, getPendingOddValue } from "../../../utils/game/bet";
import { Odd } from "../../../../general/types/odd";
import BetContent from "./BetContent.vue";

export default Vue.extend({
    name: "ParlayItem",
    components: { BetContent },
    props: {
        game: Object as PropType<DeepReadonly<Game>>,
        pendingOdd: Object as PropType<DeepReadonly<PendingOdd>>,
    },

    computed: {
        odd(): Odd | null {
            const dictionary: ReadonlyMap<string, Odd> = this.$stock.getters["odd/oddDictionary"];
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
