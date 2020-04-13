<template>
    <div class="event-frm">
        <div class="data-frm">
            <div class="trash" @click="remove">
                <i class="icon fas fa-trash-alt"></i>
            </div>

            <BetContent
                :scoreAway="scoreAway"
                :scoreHome="scoreHome"
                :startsAt="game.startsAt"
                :teamHome="game.teamHome"
                :teamAway="game.teamAway"
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
import BetContent from "./BetContent.vue";
import { Odd } from "../../../types/odd";
import { Result } from "../../../types/result";

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
            return dictionary.get(this.pendingOdd.externalId) ?? null;
        },

        team(): string {
            return getPendingOddTeam(this.pendingOdd, this.game);
        },

        oddValue(): number {
            return this.odd ? getPendingOddValue(this.pendingOdd, this.odd) : 0;
        },

        resultDict(): ReadonlyMap<string, Result> {
            return this.$stock.getters["result/resultDictionary"];
        },

        scoreHome(): number {
            return this.resultDict.get(this.game.externalId)?.home ?? 0;
        },

        scoreAway(): number {
            return this.resultDict.get(this.game.externalId)?.away ?? 0;
        },
    },

    methods: {
        remove() {
            this.$emit("delete");
        },
    },
});
</script>
