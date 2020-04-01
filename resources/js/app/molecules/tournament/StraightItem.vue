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

        <div class="bet-frm">
            <div class="field">
                <strong class="field-title">Bet</strong>
                <MoneyInput :value="value" @input="onValueChanged" />
            </div>
            <div class="field">
                <strong class="field-title">Win</strong>
                <MoneyInput class="input-win" :value="win" readonly />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { Game } from "../../types/game";
import MoneyInput from "../../components/MoneyInput.vue";
import { PendingOdd } from "../../types/window";
import { Odd } from "../../../general/types/odd";
import {
    calculateWinFromAmericanOdd,
    getPendingOddTeam,
    getPendingOddValue,
} from "../../utils/game/bet";

export default Vue.extend({
    name: "StraightItem",
    components: { MoneyInput },
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
            return calculateWinFromAmericanOdd(this.oddValue, this.pendingOdd.wager ?? 0);
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
