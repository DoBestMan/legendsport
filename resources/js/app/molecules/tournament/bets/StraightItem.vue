<template>
    <div class="bet">
        <div class="bet__details">
            <div class="bet__details__icon">
                <i class="icon icon--sport-nfl icon--micro"></i>
            </div>
            <div class="bet__details__content">
                <div class="bet__details__content__title">
                    {{ game.teamHome }} - {{ game.teamAway }}
                </div>
                <div class="bet__details__content__subtitle">{{ game.startsAt | toDateTime }}</div>
            </div>
            <div class="bet__details__icon" @click="remove">
                <i class="icon icon--delete icon--micro"></i>
            </div>
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

        <div class="bet__inputs">
            <div class="bet__inputs__input">
                <div class="label">BET</div>
                <ChipInput :value="value" @input="onValueChanged" />
            </div>
            <div class="bet__inputs__input">
                <div class="label">WIN</div>
                <ChipInput :value="win" readonly />
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
    getOddExtra,
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
            return dictionary.get(this.pendingOdd.externalId) ?? null;
        },

        win(): number {
            return Math.floor(
                calculateWinFromAmericanOdd(this.oddValue, this.pendingOdd.wager ?? 0),
            );
        },

        team(): string | null {
            return getPendingOddTeam(this.pendingOdd, this.game);
        },

        oddValue(): number {
            return this.odd ? getPendingOddValue(this.pendingOdd, this.odd) : 0;
        },

        oddExtra(): string {
            return this.odd ? getOddExtra(this.pendingOdd, this.odd) : "";
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
