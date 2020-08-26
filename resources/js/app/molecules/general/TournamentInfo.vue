<template>
    <div class="tournament">
        <div class="tournament--sidebar">
            <div class="tournament--sidebar__header">
                <div class="tournament--sidebar__header__icon">
                    <i class="icon icon--tourney" v-if="!isMobile()"></i>
                    <i class="icon icon--arrow-left" v-else @click="goToHome"></i>
                </div>
                <div class="tournament--sidebar__header__content">
                    <div class="tournament--sidebar__header__content__title">
                        {{ theTournament.name }}
                    </div>
                    <div class="tournament--sidebar__header__content__label">
                        {{ theTournament.state }}
                    </div>
                </div>
            </div>

            <div class="tournament--sidebar__details">
                <div class="tournament--sidebar__details__detail">
                    <div class="tournament--sidebar__details__detail__item">
                        <div class="tournament--sidebar__details__detail__item__label">
                            START TIME
                        </div>
                        <div class="tournament--sidebar__details__detail__item__content">
                            {{ theTournament.starts | toDateTime }}
                        </div>
                    </div>
                    <div class="tournament--sidebar__details">
                        <div class="tournament--sidebar__details__detail">
                            <div class="tournament--sidebar__details__detail__item">
                                <div class="tournament--sidebar__details__detail__item__label">
                                    # PLAYERS
                                </div>
                                <div class="tournament--sidebar__details__detail__item__content">
                                    {{ theTournament.players.length }}
                                </div>
                            </div>
                        </div>
                        <div class="tournament--sidebar__details__detail">
                            <div class="tournament--sidebar__details__detail__item">
                                <div class="tournament--sidebar__details__detail__item__label">
                                    BUY-IN
                                </div>
                                <div class="tournament--sidebar__details__detail__item__content">
                                    {{ theTournament.buyIn | formatDollars }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tournament--sidebar__details__detail">
                    <div class="tournament--sidebar__details__detail__item">
                        <div class="tournament--sidebar__details__detail__item__label">
                            Time Frame
                        </div>
                        <div class="tournament--sidebar__details__detail__item__content">
                            {{ theTournament.timeFrame }}
                        </div>
                    </div>
                    <div class="tournament--sidebar__details__detail__item">
                        <div class="tournament--sidebar__details__detail__item__label">
                            Prize Pool
                        </div>
                        <div class="tournament--sidebar__details__detail__item__content">
                            {{ theTournament.prizePoolMoney | formatDollars }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="tournament--sidebar__details">
                <div class="tournament--sidebar__details__detail">
                    <div class="tournament--sidebar__details__detail__item">
                        <div class="tournament--sidebar__details__detail__item__label">
                            Sports
                        </div>
                        <div class="tournament--sidebar__details__detail__item__content">
                            {{ sportsNames }}
                        </div>
                    </div>
                </div>
            </div>
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

    data() {
        return {
            isModalInfo: true,
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
        isMobile(): boolean {
            if (window.innerWidth < 992) return true;
            return false;
        },

        goToHome() {
            this.$router.push("/");
        },

        getSportName(sportId: string): string {
            const dict: ReadonlyMap<string, string> = this.$stock.getters["sport/sportDictionary"];
            return dict.get(sportId) ?? String(sportId);
        },
    },
});
</script>
