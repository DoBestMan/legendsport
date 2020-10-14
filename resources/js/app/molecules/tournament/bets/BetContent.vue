<template>
    <div class="bet__container">
        <div class="bet__container__content">
            <div class="bet__container__content__subtitle">
                {{ typeName }} - {{ status | capitalize }}
            </div>
            <div class="bet__container__content__title">
                {{ selectedTeam }} {{ typeExtra ? Number(typeExtra) : "" }} {{ odd | signedNumber }}
            </div>
        </div>
        <div class="bet__container__tag">
            <div class="tag tag--medium tag--color--yellow">{{ odd | signedNumber }}</div>
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
        selectedTeam: String,
        status: String as PropType<BetStatus>,
        type: String as PropType<PendingOddType>,
        typeExtra: String,
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
