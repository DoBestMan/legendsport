<template>
    <section id="tab-home-frm" class="layout__content">
        <div class="layout__content__container">
            <section>
                <SliderSection />
            </section>

            <section id="filters-frm">
                <FilterContainer />
            </section>

            <section class="layout__content__container__content">
                <div class="layout__content__container__content__sidebar">
                    <img class="image image--border" src="assets/i/rectangle@3x.png" />
                </div>

                <TournamentList
                    :selectedTournamentId="selectedTournamentId"
                    @select="updateTournamentId"
                />
            </section>
        </div>

        <div class="layout__content__sidebar">
            <TournamentDetails :tournament="selectedTournament" />
        </div>
    </section>
</template>

<script lang="ts">
import Vue from "vue";
import SliderSection from "../molecules/general/SliderSection.vue";
import FilterContainer from "../molecules/home/FilterContainer.vue";
import TournamentDetails from "../molecules/home/TournamentDetails.vue";
import TournamentList from "../molecules/home/TournamentList.vue";
import { Nullable } from "../../general/types/types";
import { Tournament } from "../types/tournament";
import { empty } from "../../general/utils/utils";

export default Vue.extend({
    name: "HomeView",
    components: { FilterContainer, TournamentDetails, TournamentList, SliderSection },

    data() {
        return {
            tournamentId: null as Nullable<number>,
        };
    },

    computed: {
        selectedTournament(): Tournament | null {
            const tournaments: Tournament[] = this.$stock.getters[
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
