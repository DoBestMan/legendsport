<template>
    <div class="layout__content__sidebar__games">
        <TournamentInfo :tournament="tournament" />

        <RegisterNowButton v-if="isRegistered" class="button--large" :tournament="tournament" />

        <div class="switch">
            <div
                class="switch__item"
                :class="{ 'switch__item--active': activeTab === 'games' }"
                @click="activeTab = 'games'"
            >
                <i class="icon icon--games m--r--1"></i>
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
import { UserPlayer } from "../../../general/types/user";

export default Vue.extend({
    name: "TournamentDetails",
    components: { RegisterNowButton, TournamentGamesTable, TournamentInfo, TournamentRankTable },

    props: {
        tournament: Object as PropType<Tournament>,
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

        isRegistered(): boolean {
            const playersDict: ReadonlyMap<number, UserPlayer> = this.$stock.getters[
                "user/playersDictByTournament"
            ];
            return playersDict.has(this.tournament.id);
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
