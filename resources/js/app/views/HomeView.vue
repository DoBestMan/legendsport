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
                    <div
                        class="layout__content__container__mobile__switch__icon"
                        @click="handleSelectWindow"
                    >
                        <i class="icon icon--down icon--micro icon--color--light-1"></i>
                    </div>
                </div>
                <div class="layout__content__container__mobile__icons">
                    <i class="icon icon--search icon--color--light-1 m--l--4"></i>
                    <i class="icon icon--filter icon--color--light-1 m--l--4"></i>
                </div>
            </div>

            <div class="modal modal--active" v-show="isMobileWindowSelected" style="top: 283px;">
                <div class="modal__row" @click="selectWindowTabs(-1)">
                    <div class="modal__row__item">
                        <i
                            class="icon icon--small icon--home m--r--2"
                            :class="{
                                'icon--color--yellow-2': isHomeSelected(),
                            }"
                        ></i>
                        Home
                    </div>
                    <div class="modal__row__item">
                        <i
                            class="icon icon--small icon--check"
                            :class="{
                                'icon--color--yellow-2': isHomeSelected(),
                            }"
                        ></i>
                    </div>
                </div>

                <div
                    v-for="window in windows"
                    :key="window.id"
                    class="modal__row"
                    @click="selectWindowTabs(window.id)"
                >
                    <div class="modal__row__item">
                        <i
                            class="icon icon--small icon--home m--r--2"
                            :class="{
                                'icon--color--yellow-2': isWindowsSelected(window.id),
                            }"
                        ></i>
                        {{ window.tournament.name }}
                    </div>
                    <div class="modal__row__item">
                        <i
                            class="icon icon--small icon--check"
                            :class="{
                                'icon--color--yellow-2': isWindowsSelected(window.id),
                            }"
                        ></i>
                    </div>
                </div>
            </div>

            <FilterContainer />

            <section class="layout__content__container__content" v-show="!isMobileWindowSelected">
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
            isMobileWindowSelected: false,
        };
    },

    computed: {
        windows(): Window[] {
            return Object.values(this.$stock.getters["window/windows"]);
        },

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

        activeWindowId(): number {
            return this.$stock.getters["window/activeWindowId"];
        },
    },

    methods: {
        updateTournamentId(tournamentId: number | null) {
            this.tournamentId = tournamentId;
        },

        handleSelectWindow(): void {
            this.isMobileWindowSelected = !this.isMobileWindowSelected;
        },

        goToHome(): void {
            this.isMobileWindowSelected = false;
        },

        selectWindowTabs(window_id: number) {
            if (window_id === -1) {
                this.$stock.commit("window/toggleWindow", window_id);
                this.$router.push("/");
            } else {
                this.$stock.commit("window/toggleWindow", window_id);
                this.$router.push(`/tournaments/${window_id}`);
            }
        },

        isHomeSelected(): boolean {
            return this.activeWindowId === -1;
        },

        isWindowsSelected(window_id: number): boolean {
            return window_id === this.activeWindowId;
        },
    },
});
</script>
