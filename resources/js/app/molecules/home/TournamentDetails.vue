<template>
    <div id="info-frm">
        <div id="title-bar-frm">
            <div id="img-frm">
                <div id="img">
                    <i class="icon fas fa-football-ball"></i>
                </div>
            </div>

            <div id="title-frm">
                <div id="title">{{ formattedTournament.name }}</div>
            </div>

            <div id="status-frm">
                <div id="status">{{ formattedTournament.state }}</div>
            </div>
        </div>

        <div id="data-frm">
            <div class="row">
                <div class="col-6">
                    <div class="title">Start time</div>
                    <div class="value">{{ formattedTournament.starts || "n/a" }}</div>
                </div>

                <div class="col-6">
                    <div class="title">In</div>
                    <div class="value"></div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="title"># Players</div>
                    <div class="value">{{ tournament.players.length }}</div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="title">Sports</div>
                    <div class="value">{{ sportsNames }}</div>
                </div>
            </div>
        </div>

        <div class="tabs-frm">
            <div class="tab-frm">
                <button
                    type="button"
                    class="tab"
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
                    class="tab"
                    :class="{ active: activeTab === 'rank' }"
                    @click="activeTab = 'rank'"
                >
                    Rank
                </button>
            </div>
        </div>

        <div class="tables-frm">
            <TournamentGamesTable v-if="activeTab === 'games'" :games="formattedTournament.games" />
            <TournamentRankTable
                v-if="activeTab === 'rank'"
                :players="formattedTournament.players"
            />
        </div>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { Tournament } from "../../types/tournament";
import { getSportName } from "../../../general/utils/sportUtils";
import TournamentGamesTable from "./TournamentGamesTable.vue";
import TournamentRankTable from "../general/TournamentRankTable.vue";
import { TournamentState } from "../../../general/types/tournament";

export default Vue.extend({
    name: "TournamentDetails",
    components: { TournamentGamesTable, TournamentRankTable },

    props: {
        tournament: {
            type: Object as PropType<Tournament>,
        },
    },

    data() {
        return {
            activeTab: "games",
        };
    },

    computed: {
        formattedTournament(): Tournament {
            if (this.tournament) {
                return this.tournament;
            }

            return {
                games: [],
                sport_ids: [],
            } as any;
        },

        sportsNames(): string {
            const sportIds = this.tournament?.sport_ids ?? [];
            return sportIds.map(getSportName).join(", ") || "n/a";
        },
    },

    methods: {
        calculateActiveTab() {
            const displayGames = [
                TournamentState.Announced,
                TournamentState.Registering,
                TournamentState.Completed,
            ].includes(this.tournament.state);

            return displayGames ? "games" : "rank";
        },
    },

    watch: {
        tournamentId(newVal, oldVal) {
            if (newVal !== oldVal) {
                this.activeTab = this.calculateActiveTab();
            }
        },
    },
});
</script>
