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
                        :startsAt="event.startsAt"
                        :homeTeam="event.homeTeam"
                        :awayTeam="event.awayTeam"
                        :selectedTeam="event.selectedTeam"
                        :odd="event.odd"
                    />
                </div>

                <div class="bet-frm">
                    <div>Bet: {{ bet.chipsWager | formatChip }}</div>
                    <div>Win: {{ bet.chipsWin | formatChip }}</div>
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
import SpinnerBox from "../../../../general/components/SpinnerBox.vue";
import { DeepReadonly } from "../../../../general/types/types";
import { Window } from "../../../types/window";
import BetContent from "./BetContent.vue";
import { User } from "../../../../general/types/user";

export default Vue.extend({
    name: "PendingTab",
    components: { BetContent, SpinnerBox },
    props: {
        window: Object as PropType<DeepReadonly<Window>>,
    },

    computed: {
        user(): User | null {
            return this.$stock.state.user.user;
        },

        bets(): Bet[] {
            return (this.user?.bets ?? []).filter(
                bet =>
                    bet.tournamentId === this.window.tournament.id &&
                    bet.status === BetStatus.Pending,
            );
        },

        isLoading(): boolean {
            return this.$stock.state.user.isLoading;
        },
    },

    methods: {
        isParlay(bet: Bet): boolean {
            return bet.events.length > 1;
        },
    },
});
</script>