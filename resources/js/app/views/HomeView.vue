<template>
    <section id="tab-home-frm" class="tab-content-frm row">
        <div class="col">
            <section id="filters-frm">
                <FilterContainer />
            </section>

            <section id="tournaments-frm" class="row">
                <div class="col-9">
                    <TournamentList
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
import FilterContainer from "../molecules/home/FilterContainer.vue";
import TournamentDetails from "../molecules/home/TournamentDetails.vue";
import TournamentList from "../molecules/home/TournamentList.vue";
import { Nullable } from "../../general/types/types";
import { Tournament } from "../types/tournament";
import { empty } from "../../general/utils/utils";

export default Vue.extend({
    name: "HomeView",
    components: { FilterContainer, TournamentDetails, TournamentList },

    data() {
        return {
            tournamentId: null as Nullable<number>,
        };
    },

    computed: {
        selectedTournament(): Tournament | null {
            const tournaments: Tournament[] = this.$store.getters[
                "tournamentList/filteredTournaments"
            ];

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
