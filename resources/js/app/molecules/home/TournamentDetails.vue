<template>
    <div id="info-frm">
        <TournamentInfo :tournament="tournament" />

        <div class="tabs-frm">
            <div class="tab-frm">
                <button
                    type="button"
                    class="btn tab"
                    :class="{ active: activeTab === 'games' }"
                    @click="activeTab = 'games'"
                >
                    Games
                </button>
                <span class="separator">|</span>
            </div>

            <div class="tab-frm">
                <button
                    type="button"
                    class="btn tab"
                    :class="{ active: activeTab === 'rank' }"
                    @click="activeTab = 'rank'"
                >
                    Rank
                </button>
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
