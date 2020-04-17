<template>
    <div class="tournament-info-frm">
        <div class="title-bar-frm">
            <div class="img-frm">
                <div class="img">
                    <i class="icon fas fa-football-ball"></i>
                </div>
            </div>

            <div class="title-frm">
                <div class="title">{{ theTournament.name }}</div>
            </div>

            <div class="status-frm">
                <div class="status">{{ theTournament.state }}</div>
            </div>
        </div>

        <div class="tournament-frm">
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
                <div class="col-4">
                    <div class="title"># Players</div>
                    <div class="value">{{ theTournament.players.length }}</div>
                </div>

                <div class="col-4">
                    <div class="title">Buy-In</div>
                    <div class="value">{{ theTournament.buyIn | formatDollars }}</div>
                </div>

                <div class="col-4">
                    <div class="title">Prize pool</div>
                    <div class="value">{{ theTournament.prizePoolMoney | formatDollars }}</div>
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
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { Tournament } from "../../types/tournament";
import RegisterNowButton from "../../components/RegisterNowButton.vue";
import { UserPlayer } from "../../../general/types/user";

export default Vue.extend({
    name: "TournamentInfo",
    components: { RegisterNowButton },

    props: {
        tournament: Object as PropType<Tournament | null>,
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

        sportsNames(): string {
            return this.tournament?.sportIds.map(this.getSportName).join(", ") || "n/a";
        },

        isRegistered(): boolean {
            const playersDict: ReadonlyMap<number, UserPlayer> = this.$stock.getters[
                "user/playersDictByTournament"
            ];
            return playersDict.has(this.theTournament.id);
        },
    },

    methods: {
        getSportName(sportId: string): string {
            const dict: ReadonlyMap<string, string> = this.$stock.getters["sport/sportDictionary"];
            return dict.get(sportId) ?? String(sportId);
        },
    },
});
</script>
