<template>
    <section class="layout__content layout__content--home">
        <div class="layout__content__container">
            <SliderSection />

            <div class="layout__content__container__mobile">
                <div class="layout__content__container__mobile__switch">
                    <div class="layout__content__container__mobile__switch__icon">
                        <i class="icon icon--home icon--micro"></i>
                    </div>
                    <div class="layout__content__container__mobile__switch__title">
                        Home
                    </div>
                    <div class="layout__content__container__mobile__switch__icon">
                        <i class="icon icon--down icon--micro icon--color--light-1"></i>
                    </div>
                </div>
                <div class="layout__content__container__mobile__icons">
                    <i class="icon icon--search icon--color--light-1 m--l--4"></i>
                    <i class="icon icon--filter icon--color--light-1 m--l--4"></i>
                </div>
            </div>

            <FilterContainer />

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
