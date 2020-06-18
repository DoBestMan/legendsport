<template>
    <div class="event-frm">
        <div class="data-frm">
            <div class="trash" @click="remove">
                <i class="icon fas fa-trash-alt"></i>
            </div>

            <BetContent
                :scoreAway="game.scoreAway"
                :scoreHome="game.scoreHome"
                :startsAt="game.startsAt"
                :teamHome="game.teamHome"
                :teamAway="game.teamAway"
                :selectedTeam="team"
                :odd="oddValue"
                :type="pendingOdd.type"
                :type-extra="oddExtra"
            />
        </div>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { DeepReadonly } from "../../../../general/types/types";
import { Game } from "../../../types/game";
import { PendingOdd } from "../../../types/window";
import {getOddExtra, getPendingOddTeam, getPendingOddValue} from "../../../utils/game/bet";
import BetContent from "./BetContent.vue";
import { Odd } from "../../../types/odd";

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

        team(): string | null {
            return getPendingOddTeam(this.pendingOdd, this.game);
        },

        oddValue(): number {
            return this.odd ? getPendingOddValue(this.pendingOdd, this.odd) : 0;
        },

        oddExtra(): string {
            return this.odd ? getOddExtra(this.pendingOdd, this.odd) : "";
        }
    },

    methods: {
        remove() {
            this.$emit("delete");
        },
    },
});
</script>
