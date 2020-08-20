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
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { DeepReadonly } from "../../../../general/types/types";
import { Game } from "../../../types/game";
import { PendingOdd } from "../../../types/window";
import { getOddExtra, getPendingOddTeam, getPendingOddValue } from "../../../utils/game/bet";
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
        },
    },

    methods: {
        remove() {
            this.$emit("delete");
        },
    },
});
</script>
