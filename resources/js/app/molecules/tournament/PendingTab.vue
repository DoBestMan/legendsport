<template>
    <div class="tab-content-frm">
        <SpinnerBox v-if="isLoading" />

        <div v-else class="items-frm">
            <div :key="bet.id" class="event-frm" v-for="bet in bets">
                <div class="data-frm" v-for="(event, index) in bet.events">
                    <div v-if="index === 0" class="type-bet">
                        <span v-if="isParlay(bet)">Parlay</span>
                        <span v-else>Straight</span>
                    </div>

                    <div class="text">{{ event.starts | toDateTime }}</div>
                    <div class="text game-frm">
                        <div class="text team">{{ event.home_team }}</div>
                        <div class="text score">0</div>
                        <div class="text vs">@</div>
                        <div class="text team">{{ event.away_team }}</div>
                        <div class="text score">0</div>
                    </div>
                    <div class="text">{{ event.selected_team }} / {{ event.odd | formatOdd }}</div>
                </div>

                <div class="bet-frm">
                    <div>Bet: {{ bet.chips_wager | formatCurrency }}</div>
                    <div>Win: {{ bet.chips_win | formatCurrency }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import { Bet } from "../../types/bet";
import SpinnerBox from "../../../general/components/SpinnerBox.vue";

export default Vue.extend({
    name: "PendingTab",
    components: { SpinnerBox },

    computed: {
        bets(): Bet[] {
            return this.$stock.state.bet.bets;
        },

        isLoading(): boolean {
            return this.$stock.state.bet.isLoading;
        },
    },

    methods: {
        isParlay(bet: Bet): boolean {
            return bet.events.length > 1;
        },
    },
});
</script>
