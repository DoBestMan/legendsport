<template>
    <div class="layout__full" style="max-height: calc(100% - 62px);">
        <div class="container">
            <div class="paging d--only--desktop">
                <div class="paging__item">
                    <i
                        class="icon icon--left icon--large icon--color--light-1 m--r--4"
                        @click="goToHome"
                    ></i>
                    <div class="paging__item__title">History</div>
                </div>
            </div>

            <div class="layout__content layout__content--border">
                <div class="layout__content__container">
                    <div class="layout__content__container__mobile --expand">
                        <div class="layout__content__container__mobile__switch">
                            <div class="layout__content__container__mobile__switch__icon">
                                <i
                                    class="icon icon--left icon--color--light-1"
                                    @click="goToHome"
                                ></i>
                            </div>
                            <div class="layout__content__container__mobile__switch__title">
                                History
                            </div>
                        </div>
                        <div class="layout__content__container__mobile__icons">
                            <i class="icon icon--search icon--color--light-1 m--l--4"></i>
                            <i class="icon icon--sort icon--color--light-1 m--l--4"></i>
                        </div>
                    </div>

                    <div class="layout__content__container__content">
                        <TournamentHistoryList
                            :selectedTournamentId="selectedTournamentId"
                            @select="updateTournamentId"
                        />
                    </div>
                </div>

                <div
                    class="layout__content__sidebar b--transparent layout__content__sidebar--right"
                >
                    <div class="layout__content__sidebar__chat">
                        <div class="layout__content__sidebar__chat__cta">
                            <div class="layout__content__sidebar__chat__cta__title">
                                <i
                                    class="icon icon--micro icon--slip icon--color--light-1 m--r--2"
                                ></i>
                                BET SLIP
                            </div>
                            <div class="layout__content__sidebar__chat__cta__action">
                                <span class="tag tag--color--red tag--medium m--r--2">{{
                                    bets.length
                                }}</span>
                                <i
                                    class="icon icon--micro icon--expand icon--color--light-2"
                                    v-if="!isSlipExpanded"
                                    @click="handleExpandSlip"
                                ></i>
                                <i
                                    class="icon icon--micro icon--close icon--color--light-2"
                                    v-else
                                    @click="handleExpandSlip"
                                ></i>
                            </div>
                        </div>

                        <div
                            class="layout__content__sidebar__chat__container"
                            v-show="isSlipExpanded"
                        >
                            <div class="layout__content__sidebar__chat__container__messages">
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
                                                    class="bet__header__tag bet__header__tag--green"
                                                >
                                                    PUSH
                                                </div>
                                            </div>

                                            <div class="bet__details">
                                                <div class="bet__details__content">
                                                    <div class="bet__details__content__title">
                                                        {{ event.teamHome }} - {{ event.teamAway }}
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

                                            <div
                                                class="bet__seperator"
                                                v-show="index !== bet.events.length - 1"
                                            />
                                        </div>

                                        <div class="bet__footer">
                                            <div class="bet__footer__line">
                                                <div class="bet__footer__line__name">Total Bet</div>
                                                <div class="bet__footer__line__detail">
                                                    {{ bet.chipsWager | formatChip }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="layout__content__sidebar__games">
                        <TournamentInfo :tournament="selectedTournament" />
                        <TournamentHistoryInfo :tournament="selectedTournament" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import TournamentDetails from "../molecules/home/TournamentDetails.vue";
import TournamentHistoryList from "../molecules/history/TournamentHistoryList.vue";
import TournamentHistoryInfo from "../molecules/history/TournamentHistoryInfo.vue";
import TournamentInfo from "../molecules/general/TournamentInfo.vue";
import BetContent from "../molecules/tournament/bets/BetContent.vue";
import { Nullable } from "../../general/types/types";
import { Tournament } from "../types/tournament";
import { empty } from "../../general/utils/utils";
import { DeepReadonly } from "../../general/types/types";
import { Window } from "../types/window";
import { Bet, BetStatus } from "../types/bet";

export default Vue.extend({
    name: "HistoryView",
    components: {
        TournamentDetails,
        TournamentHistoryList,
        TournamentInfo,
        TournamentHistoryInfo,
        BetContent,
    },

    data() {
        return {
            tournamentId: null as Nullable<number>,
            isSlipExpanded: false,
        };
    },

    computed: {
        selectedTournament(): Tournament | null {
            const tournaments: Tournament[] = this.$stock.state.tournamentHistoryList.tournaments;

            if (empty(tournaments)) {
                return null;
            }

            const tournament = tournaments.find(tournament => tournament.id === this.tournamentId);
            if (tournament) {
                return tournament;
            }

            return tournaments[0];
        },

        selectedTournamentId(): number | null {
            return this.selectedTournament?.id ?? null;
        },

        window(): DeepReadonly<Window> | null {
            return (
                this.$stock.getters["window/windows"].find(
                    (window: Window) => window.id === this.selectedTournamentId,
                ) ?? null
            );
        },

        bets(): Bet[] {
            return (this.$stock.state.user.user?.bets ?? []).filter(
                bet =>
                    bet.tournamentId === this.window?.tournament.id &&
                    bet.status !== BetStatus.Pending,
            );
        },

        BetStatus(): typeof BetStatus {
            return BetStatus;
        },
    },

    methods: {
        goToHome(): void {
            this.$router.push("/");
        },

        updateTournamentId(tournamentId: number | null) {
            this.tournamentId = tournamentId;
        },

        handleExpandSlip(): void {
            this.isSlipExpanded = !this.isSlipExpanded;
        },

        isParlay(bet: Bet): boolean {
            return bet.events.length > 1;
        },
    },
});
</script>
