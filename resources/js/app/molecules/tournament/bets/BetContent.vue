<template>
    <div>
        <div class="text">{{ startsAt | toDateTime }}</div>
        <div class="text game-frm">
            <div class="text team">{{ teamHome }}</div>
            <div class="text score">{{ scoreHome }}</div>
            <div class="text vs">@</div>
            <div class="text team">{{ teamAway }}</div>
            <div class="text score">{{ scoreAway }}</div>
        </div>
        <div class="text">
            <span>{{ selectedTeam }}</span>
            <span> / {{ odd | signedNumber }}</span>
            <span v-if="status"> / {{ status | capitalize }}</span>
            <span> - {{ typeName }}</span>
        </div>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { BetStatus } from "../../../types/bet";
import { PendingOddType } from "../../../types/window";

export default Vue.extend({
    name: "BetContent",
    props: {
        odd: Number,
        scoreAway: Number,
        scoreHome: Number,
        selectedTeam: String,
        startsAt: String,
        teamAway: String,
        teamHome: String,
        status: String as PropType<BetStatus>,
        type: String as PropType<PendingOddType>,
    },

    computed: {
        typeName(): string {
            if ([PendingOddType.MoneyLineAway, PendingOddType.MoneyLineHome].includes(this.type)) {
                return "Money Line";
            }

            if ([PendingOddType.SpreadAway, PendingOddType.SpreadHome].includes(this.type)) {
                return "Spread";
            }

            if (this.type === PendingOddType.TotalOver) {
                return "Total Over";
            }

            if (this.type === PendingOddType.TotalUnder) {
                return "Total Under";
            }

            return "";
        },
    },
});
</script>
