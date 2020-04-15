<template>
    <div class="tab-content-frm">
        <SpinnerBox v-if="isLoading" />

        <div v-else class="items-frm">
            <div :key="bet.id" class="event-frm" v-for="bet in bets">
                <div class="data-frm" v-for="(event, index) in bet.events">
                    <div v-if="index === 0" class="tag type-bet">
                        <span v-if="isParlay(bet)">Parlay</span>
                        <span v-else>Straight</span>
                    </div>

                    <BetContent
                        :scoreAway="event.scoreAway"
                        :scoreHome="event.scoreHome"
                        :startsAt="event.startsAt"
                        :teamHome="event.teamHome"
                        :teamAway="event.teamAway"
                        :selectedTeam="event.selectedTeam"
                        :odd="event.odd"
                        :status="event.status"
                        :type="event.type"
                    />
                </div>

                <div class="bet-frm">
                    <div>Bet: {{ bet.chipsWager | formatChip }}</div>
                    <div>Win: {{ bet.chipsWin | formatChip }}</div>
                </div>

                <div v-if="bet.status === BetStatus.Win" class="result win">
                    <i class="icon fas fa-laugh-beam"></i> YOU WON!
                </div>

                <div v-else-if="bet.status === BetStatus.Loss" class="result lost">
                    <i class="icon fas fa-frown"></i> YOU LOST!
                </div>

                <div v-else-if="bet.status === BetStatus.Push" class="result push">
                    <i class="icon fas fa-meh"></i> PUSH
                </div>
            </div>

            <div v-if="!bets.length" class="h3 text-center p-5">
                No records
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { Bet, BetStatus } from "../../../types/bet";
import BetContent from "./BetContent.vue";
import SpinnerBox from "../../../../general/components/SpinnerBox.vue";
import { DeepReadonly } from "../../../../general/types/types";
import { Window } from "../../../types/window";

export default Vue.extend({
    name: "HistoryTab",
    components: { BetContent, SpinnerBox },
    props: {
        window: Object as PropType<DeepReadonly<Window>>,
    },

    computed: {
        bets(): Bet[] {
            return (this.$stock.state.user.user?.bets ?? []).filter(
                bet =>
                    bet.tournamentId === this.window.tournament.id &&
                    bet.status !== BetStatus.Pending,
            );
        },

        isLoading(): boolean {
            return this.$stock.state.user.isLoading;
        },

        BetStatus(): typeof BetStatus {
            return BetStatus;
        },
    },

    methods: {
        isParlay(bet: Bet): boolean {
            return bet.events.length > 1;
        },
    },
});
</script>
