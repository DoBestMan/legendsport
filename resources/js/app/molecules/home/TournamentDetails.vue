<template>
    <div id="info-frm">
        <div id="title-bar-frm">
            <div id="img-frm">
                <div id="img">
                    <i class="icon fas fa-football-ball"></i>
                </div>
            </div>

            <div id="title-frm">
                <div id="title">{{ theTournament.name }}</div>
            </div>

            <div id="status-frm">
                <div id="status">{{ theTournament.state }}</div>
            </div>
        </div>

        <div id="data-frm">
            <div class="row">
                <div class="col-6">
                    <div class="title">Start time</div>
                    <div class="value">{{ theTournament.starts | toDateTime }}</div>
                </div>

                <div class="col-6">
                    <div class="title">In</div>
                    <div class="value">{{ theTournament.starts | diffHumanReadable }}</div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="title"># Players</div>
                    <div class="value">{{ theTournament.players.length }}</div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="title">Sports</div>
                    <div class="value">{{ sportsNames }}</div>
                </div>
            </div>

            <RegisterNowButton
                v-if="tournament && !isRegistered"
                class="mb-3 mt-1"
                :tournament="tournament"
            />
        </div>

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

        <div class="tables-frm">
            <TournamentGamesTable v-if="activeTab === 'games'" :games="theTournament.games" />
            <TournamentRankTable v-if="activeTab === 'rank'" :players="theTournament.players" />
        </div>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { Tournament } from "../../types/tournament";
import TournamentGamesTable from "./TournamentGamesTable.vue";
import TournamentRankTable from "../general/TournamentRankTable.vue";
import { TournamentState } from "../../../general/types/tournament";
import RegisterNowButton from "../../components/RegisterNowButton.vue";
import { UserPlayer } from "../../../general/types/user";

export default Vue.extend({
    name: "TournamentDetails",
    components: { RegisterNowButton, TournamentGamesTable, TournamentRankTable },

    props: {
        tournament: Object as PropType<Tournament>,
    },

    data() {
        return {
            activeTab: "games",
        };
    },

    computed: {
        theTournament(): Tournament {
            if (this.tournament) {
                return this.tournament;
            }

            return {
                games: [],
                players: [],
                sport_ids: [],
            } as any;
        },

        isRegistered(): boolean {
            const playersDict: ReadonlyMap<number, UserPlayer> = this.$stock.getters[
                "user/playersDictByTournament"
            ];
            return playersDict.has(this.theTournament.id);
        },

        sportsNames(): string {
            const sportsIds = this.tournament?.sportIds ?? [];
            const dict: ReadonlyMap<number, string> = this.$stock.getters["sport/sportDictionary"];
            return sportsIds.map(sportId => dict.get(sportId) ?? sportId).join(", ") || "n/a";
        },
    },

    methods: {
        calculateActiveTab(): string {
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
