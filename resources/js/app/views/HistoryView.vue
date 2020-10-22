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
    },

    methods: {
        goToHome(): void {
            this.$router.push("/lobby");
        },

        updateTournamentId(tournamentId: number | null) {
            this.tournamentId = tournamentId;
        },
    },
});
</script>
