<template>
    <SpinnerBox v-if="isLoading" />

    <div v-else class="layout__content__sidebar__games">
        <div class="bet">
            <div :key="bet.id" v-for="bet in bets">
                <div :key="betEvent.id" v-for="(betEvent, index) in bet.events">
                    <div class="bet__header" v-if="index === 0">
                        <div class="bet__header__type">
                            <span v-if="isParlay(bet)">Parlay</span>
                            <span v-else>Straight</span>
                        </div>
                    </div>

                    <div class="bet__details">
                        <div class="bet__details__icon">
                            <i class="icon icon--sport-nfl icon--micro"></i>
                        </div>
                        <div class="bet__details__content">
                            <div class="bet__details__content__title">
                                {{ betEvent.scoreHome }} {{ betEvent.teamHome }} - {{ betEvent.scoreAway }} {{ betEvent.teamAway }}
                                <span style="padding-left: 5px;" v-if="isGameEnded(betEvent.externalId)"> F </span>
                            </div>
                            <div class="bet__details__content__subtitle">
                                {{ betEvent.startsAt | toDateTime }}
                            </div>
                        </div>
                    </div>

                    <BetContent
                        :scoreAway="betEvent.scoreAway"
                        :scoreHome="betEvent.scoreHome"
                        :startsAt="betEvent.startsAt"
                        :teamHome="betEvent.teamHome"
                        :teamAway="betEvent.teamAway"
                        :selectedTeam="betEvent.selectedTeam"
                        :odd="betEvent.odd"
                        :status="betEvent.status"
                        :type="betEvent.type"
                        :type-extra="betEvent.handicap"
                    />
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
import SpinnerBox from "../../../../general/components/SpinnerBox.vue";
import { DeepReadonly } from "../../../../general/types/types";
import { Window } from "../../../types/window";
import { Game, GameState } from "../../../types/game";
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

        gameDict(): ReadonlyMap<string, Game> {
            return new Map(this.window.tournament.games.map(game => [game.externalId, game]));
        },

        isLoading(): boolean {
            return this.$stock.state.user.isLoading;
        },
    },

    methods: {
        isParlay(bet: Bet): boolean {
            return bet.events.length > 1;
        },

        isGameEnded(externalId: string): boolean {
            if (this.gameDict?.has(externalId)) {
                if (this.gameDict?.get(externalId)?.timeStatus === GameState.Ended) {
                    return true;
                }
            }
            return false;
        },

    },
});
</script>
