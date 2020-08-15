<template>
    <SpinnerBox v-if="isLoading" />

    <div v-else class="bet bets__container__scroll">
        <div :key="bet.id" v-for="bet in bets">
            <div :key="event.id" v-for="(event, index) in bet.events">
                <div class="bet__header" v-if="index === 0">
                    <div class="bet__header__type">
                        <span v-if="isParlay(bet)">Parlay</span>
                        <span v-else>Straight</span>
                    </div>

                    <div
                        v-if="bet.status === BetStatus.Win"
                        class="bet__header__tag bet__header__tag--red"
                    >
                        WON
                    </div>

                    <div
                        v-else-if="bet.status === BetStatus.Loss"
                        class="bet__header__tag bet__header__tag--red"
                    >
                        LOST
                    </div>

                    <!-- ToDo: make sure 'PUSH' -->
                    <div
                        v-else-if="bet.status === BetStatus.Push"
                        class="bet__header__tag bet__header__tag--red"
                    >
                        PUSH
                    </div>
                </div>

                <div class="bet__details">
                    <div class="bet__details__icon" @click="remove">
                        <i class="icon icon--sport-nfl icon--micro"></i>
                    </div>
                    <div class="bet__details__content">
                        <!-- TODO: -->

                        <!-- <div class="text team">{{ teamHome }}</div>
                            <div class="text score">{{ scoreHome | score }}</div>
                            <div class="text vs">@</div>
                            <div class="text team">{{ teamAway }}</div>
                        <div class="text score">{{ scoreAway | score }}</div>-->
                        <div class="bet__details__content__title">
                            {{ event.teamHome }} - {{ event.teamAway }}
                        </div>
                        <div class="bet__details__content__subtitle">
                            {{ event.startsAt | toDateTime }}
                        </div>
                    </div>
                    <div class="bet__details__icon">
                        <i class="icon icon--delete icon--micro"></i>
                    </div>
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
                    :type-extra="event.handicap"
                />
            </div>

            <div class="bet__footer">
                <div class="bet__footer__line">
                    <div class="bet__footer__line__name">Total Bet</div>
                    <div class="bet__footer__line__detail">{{ bet.chipsWager | formatChip }}</div>
                </div>
                <div class="bet__footer__line">
                    <div class="bet__footer__line__name">Total Win</div>
                    <div class="bet__footer__line__detail">{{ bet.chipsWin | formatChip }}</div>
                </div>
            </div>

            <!-- ToDo: -->

            <!-- <div class="bet-frm">
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
            </div> -->
        </div>

        <div v-if="!bets.length" class="h3 text-center p-5">
            No records
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
