<template>
    <section id="tab-home-frm" class="tab-content-frm row">
        <div class="col">
            <section id="tournaments-frm" class="row">
                <div class="col-9">
                    <TournamentHistoryList
                        :selectedTournamentId="selectedTournamentId"
                        @select="updateTournamentId"
                    />
                </div>

                <div class="col-3">
                    <TournamentDetails :tournament="selectedTournament" />
                </div>
            </section>
        </div>
    </section>
</template>

<script lang="ts">
import Vue from "vue";
import TournamentDetails from "../molecules/home/TournamentDetails.vue";
import TournamentHistoryList from "../molecules/history/TournamentHistoryList.vue";
import { Nullable } from "../../general/types/types";
import { Tournament } from "../types/tournament";
import { empty } from "../../general/utils/utils";

export default Vue.extend({
    name: "HistoryView",
    components: { TournamentDetails, TournamentHistoryList },

    data() {
        return {
            tournamentId: null as Nullable<number>,
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
    },

    methods: {
        updateTournamentId(tournamentId: number | null) {
            this.tournamentId = tournamentId;
        },
    },
});
</script>
