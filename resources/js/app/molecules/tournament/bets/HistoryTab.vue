<template>
    <SpinnerBox v-if="isLoading" />

    <div v-else class="layout__content__sidebar__games">
        <div class="bet">
            <div :key="bet.id" v-for="bet in bets">
                <div :key="event.id" v-for="(event, index) in bet.events">
                    <div class="bet__header" v-if="index === 0">
                        <div class="bet__header__type">
                            <span v-if="isParlay(bet)">Parlay</span>
                            <span v-else>Straight</span>
                        </div>

                        <div
                            v-if="bet.status === BetStatus.Win"
                            class="bet__header__tag bet__header__tag--green"
                        >
                            WON
                        </div>

                        <div
                            v-else-if="bet.status === BetStatus.Loss"
                            class="bet__header__tag bet__header__tag--red"
                        >
                            LOST
                        </div>

                        <div
                            v-else-if="bet.status === BetStatus.Push"
                            class="bet__header__tag bet__header__tag--yellow"
                        >
                            PUSH
                        </div>
                    </div>

                    <div class="bet__details">
                        <div class="bet__details__content">
                            <div class="bet__details__content__title">
                                {{ event.teamHome }} {{ event.scoreHome }} - {{ event.teamAway }}
                                {{ event.scoreAway }}
                            </div>
                            <div class="bet__details__content__subtitle">
                                {{ event.startsAt | toDateTime }}
                            </div>
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

                    <div class="bet__seperator" v-show="index !== bet.events.length - 1" />
                </div>

                <div class="bet__footer">
                    <div class="bet__footer__line">
                        <div class="bet__footer__line__name">Total Bet</div>
                        <div class="bet__footer__line__detail">
                            {{ bet.chipsWager | formatChip }}
                        </div>
                    </div>
                    <div class="bet__footer__line">
                        <div class="bet__footer__line__name">Total Win</div>
                        <div class="bet__footer__line__detail">{{ bet.chipsWin | formatChip }}</div>
                    </div>
                </div>
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
