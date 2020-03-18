<template>
    <div id="info-frm">
        <div id="title-bar-frm">
            <div id="img-frm">
                <div id="img">
                    <i class="icon fas fa-football-ball"></i>
                </div>
            </div>

            <div id="title-frm">
                <div id="title">{{ tournament.name }}</div>
            </div>

            <div id="status-frm">
                <div id="status">{{ tournament.state }}</div>
            </div>
        </div>

        <div id="data-frm">
            <div class="row">
                <div class="col-6">
                    <div class="title">Start time</div>
                    <div class="value">{{ tournament.starts || "n/a" }}</div>
                </div>

                <div class="col-6">
                    <div class="title">In</div>
                    <div class="value"></div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="title"># Players</div>
                    <div class="value">0</div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="title">Sports</div>
                    <div class="value">{{ sportsNames }}</div>
                </div>
            </div>
        </div>

        <table class="table tabs">
            <thead class="thead">
                <tr class="tr">
                    <th :class="{ active: activeTab === 'games' }" @click="activeTab = 'games'">
                        Games
                    </th>
                    <th :class="{ active: activeTab === 'rank' }" @click="activeTab = 'rank'">
                        Rank
                    </th>
                </tr>
            </thead>
        </table>

        <div class="tables-frm">
            <TournamentGamesTable v-if="activeTab === 'games'" :games="tournament.games" />
            <TournamentRankTable v-if="activeTab === 'rank'" />
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import { Tournament } from "../../types/tournament";
import tournamentListStore from "../../stores/tournamentListStore";
import { getSportName } from "../../../general/utils/sportUtils";
import TournamentGamesTable from "./TournamentGamesTable.vue";
import TournamentRankTable from "./TournamentRankTable.vue";
import { TournamentState } from "../../../general/types/tournament";
import { empty } from "../../../general/utils/utils";

export default Vue.extend({
    name: "TournamentDetails",
    components: { TournamentGamesTable, TournamentRankTable },

    props: {
        tournamentId: {
            type: Number,
        },
    },

    data() {
        return {
            activeTab: "games",
        };
    },

    computed: {
        tournament(): Tournament {
            const foundTournament = tournamentListStore.tournaments.find(
                tournament => tournament.id === this.tournamentId,
            );

            if (foundTournament) {
                return foundTournament;
            }

            if (!empty(tournamentListStore.tournaments)) {
                return tournamentListStore.tournaments[0];
            }

            return {
                games: [],
                sport_ids: [],
            } as any;
        },

        sportsNames(): string {
            // @ts-ignore
            if (!this.tournament) {
                return "n/a";
            }

            // @ts-ignore
            return this.tournament.sport_ids.map(getSportName).join(", ") || "n/a";
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

<style lang="scss">
.tabs {
    th {
        cursor: pointer;
        font-size: 1.2rem;

        &.active {
            text-decoration: underline;
            font-weight: bold;
        }
    }
}
</style>
