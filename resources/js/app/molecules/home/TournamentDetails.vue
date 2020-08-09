<template>
    <div class="layout__content__sidebar__games">
        <TournamentInfo :tournament="tournament" />

        <div class="switch">
            <div
                class="switch__item"
                :class="{ 'switch__item--active': activeTab === 'games' }"
                @click="activeTab = 'games'"
            >
                <i class="icon icon--color--dark-4 icon--games m--r--1"></i>
                GAMES
            </div>
            <div
                class="switch__item"
                :class="{ 'switch__item--active': activeTab === 'rank' }"
                @click="activeTab = 'rank'"
            >
                <i class="icon icon--micro icon--rank m--r--1"></i>
                RANKS
            </div>
        </div>

        <TournamentGamesTable v-if="activeTab === 'games'" :games="games" />
        <TournamentRankTable v-if="activeTab === 'rank'" :players="players" />
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { Tournament } from "../../types/tournament";
import TournamentGamesTable from "./TournamentGamesTable.vue";
import TournamentRankTable from "../general/TournamentRankTable.vue";
import { TournamentState } from "../../../general/types/tournament";
import RegisterNowButton from "../../components/RegisterNowButton.vue";
import TournamentInfo from "../general/TournamentInfo.vue";
import { Game } from "../../types/game";
import { Player } from "../../types/player";

export default Vue.extend({
    name: "TournamentDetails",
    components: { RegisterNowButton, TournamentGamesTable, TournamentInfo, TournamentRankTable },

    props: {
        tournament: Object as PropType<Tournament | null>,
    },

    data() {
        return {
            activeTab: "games",
        };
    },

    computed: {
        games(): Game[] {
            return this.tournament?.games ?? [];
        },

        players(): Player[] {
            return this.tournament?.players ?? [];
        },
    },

    methods: {
        calculateActiveTab(): string {
            const gameStates = [
                TournamentState.Announced,
                TournamentState.Registering,
                TournamentState.Completed,
            ];

            return this.tournament && gameStates.includes(this.tournament.state) ? "games" : "rank";
        },
    },

    watch: {
        tournament(newVal, oldVal) {
            if (newVal !== oldVal) {
                this.activeTab = this.calculateActiveTab();
            }
        },
    },
});
</script>
