<template>
    <div class="layout__full">
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
                                <span class="tag tag--color--red tag--medium m--r--2">1</span>
                                <i
                                    class="icon icon--micro icon--expand icon--color--light-2"
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
                                    <div class="bet__header">
                                        <div class="bet__header__type">
                                            Straight
                                        </div>
                                    </div>
                                    <div class="bet__details">
                                        <div class="bet__details__icon">
                                            <i class="icon icon--sport-nfl icon--micro"></i>
                                        </div>
                                        <div class="bet__details__content">
                                            <div class="bet__details__content__title">
                                                Houston Texans - Kansas City Chiefs
                                            </div>
                                            <div class="bet__details__content__subtitle">
                                                Jul, 10 at 19.30ET
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bet__container">
                                        <div class="bet__container__content">
                                            <div class="bet__container__content__subtitle">
                                                Moneyline - Full Time
                                            </div>
                                            <div class="bet__container__content__title">
                                                Houston Texans +10
                                            </div>
                                        </div>
                                        <div class="bet__container__tag">
                                            <div class="tag tag--medium tag--color--yellow">
                                                -110
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bet__footer">
                                        <div class="bet__footer__line">
                                            <div class="bet__footer__line__name">
                                                Total Bet
                                            </div>
                                            <div class="bet__footer__line__detail">
                                                $ 100.00
                                            </div>
                                        </div>
                                        <div class="bet__footer__line">
                                            <div class="bet__footer__line__name">
                                                Total Win
                                            </div>
                                            <div class="bet__footer__line__detail">
                                                $ 100o.00
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="layout__content__sidebar__games">
                        <TournamentInfo :tournament="selectedTournament" />
                        <InfoDetailSection :tournament="selectedTournament" :window="window" />
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
import TournamentInfo from "../molecules/general/TournamentInfo.vue";
import InfoDetailSection from "../molecules/tournament/info/InfoDetailSection.vue";
import { Nullable } from "../../general/types/types";
import { Tournament } from "../types/tournament";
import { empty } from "../../general/utils/utils";
import { DeepReadonly } from "../../general/types/types";
import { Window } from "../types/window";

export default Vue.extend({
    name: "HistoryView",
    components: { TournamentDetails, TournamentHistoryList, TournamentInfo, InfoDetailSection },

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
    },
});
</script>
