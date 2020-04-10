<template>
    <div class="event-frm">
        <div class="data-frm">
            <div class="trash" @click="remove">
                <i class="icon fas fa-trash-alt"></i>
            </div>

            <BetContent
                :startsAt="game.starts_at"
                :homeTeam="game.home_team"
                :awayTeam="game.away_team"
                :selectedTeam="team"
                :odd="oddValue"
            />
        </div>

        <div class="bet-frm">
            <div class="field">
                <strong class="field-title">Bet</strong>
                <ChipInput :value="value" @input="onValueChanged" />
            </div>
            <div class="field">
                <strong class="field-title">Win</strong>
                <ChipInput class="input-win" :value="win" readonly />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { Game } from "../../../types/game";
import { PendingOdd } from "../../../types/window";
import {
    calculateWinFromAmericanOdd,
    getPendingOddTeam,
    getPendingOddValue,
} from "../../../utils/game/bet";
import BetContent from "./BetContent.vue";
import ChipInput from "../../../../general/components/ChipInput.vue";
import { Odd } from "../../../types/odd";

export default Vue.extend({
    name: "StraightItem",
    components: { BetContent, ChipInput },

    props: {
        game: Object as PropType<Game>,
        pendingOdd: Object as PropType<PendingOdd>,
        value: Number,
    },

    computed: {
        odd(): Odd | null {
            const dictionary: ReadonlyMap<string, Odd> = this.$stock.getters["odd/oddDictionary"];
            return dictionary.get(this.pendingOdd.eventId) ?? null;
        },

        win(): number {
            return Math.floor(
                calculateWinFromAmericanOdd(this.oddValue, this.pendingOdd.wager ?? 0),
            );
        },

        team(): string {
            return getPendingOddTeam(this.pendingOdd, this.game);
        },

        oddValue(): number {
            return this.odd ? getPendingOddValue(this.pendingOdd, this.odd) : 0;
        },
    },

    methods: {
        onValueChanged(wager: number) {
            this.$emit("change", wager);
        },

        remove() {
            this.$emit("delete");
        },
    },
});
</script>
