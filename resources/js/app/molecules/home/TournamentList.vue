<template>
    <div class="layout__content__container__content__container">
        <div class="table d--only--desktop">
            <div class="table__header">
                <div class="table__header__label">START</div>
                <div class="table__header__label">TOURNAMENT NAME</div>
                <div class="table__header__label">BUY-IN</div>
                <div class="table__header__label">TIME FRAME</div>
                <div class="table__header__label">STATUS</div>
                <div class="table__header__label">PLAYERS</div>
            </div>
        </div>
        <div class="tournament">
            <div
                class="tournament--desktop"
                @click="selectTournament(tournament)"
                @dblclick="openTournament(tournament)"
                v-for="tournament in filteredTournaments"
                :key="tournament.id"
            >
                <div class="tournament--desktop__item">{{ tournament.starts | toDateTime }}</div>
                <div class="tournament--desktop__item">
                    <span
                        v-if="isRegistered(tournament)"
                        title="You're registered for this tournament"
                    >
                        <strong>{{ tournament.name }}</strong>
                        <i class="icon icon--smaller icon--check icon--color--yellow-1" />
                    </span>
                    <span v-else>{{ tournament.name }}</span>
                </div>
                <div class="tournament--desktop__item">{{ tournament.buyIn | formatDollars }}</div>
                <div class="tournament--desktop__item">{{ tournament.timeFrame }}</div>
                <div class="tournament--desktop__item">{{ tournament.state }}</div>
                <div class="tournament--desktop__item">{{ tournament.players.length }}</div>
            </div>
            <div
                class="tournament--mobile"
                @click="selectTournament(tournament)"
                @dblclick="openTournament(tournament)"
                v-for="tournament in filteredTournaments"
                :key="tournament.id"
            >
                <div class="tournament--mobile__container">
                    <div class="tournament--mobile__container__sidebar">
                        <div class="tournament--mobile__container__sidebar__date">
                            <div class="tournament--mobile__container__sidebar__date__weekday">
                                WED
                            </div>
                            <div class="tournament--mobile__container__sidebar__date__day">
                                24
                            </div>
                            <div class="tournament--mobile__container__sidebar__date__month">
                                JUN
                            </div>
                        </div>
                        <div class="tournament--mobile__container__sidebar__time">
                            <div class="tournament--mobile__container__sidebar__time__hour">
                                23.30
                            </div>
                            <div class="tournament--mobile__container__sidebar__time__timezone">
                                ET
                            </div>
                        </div>
                    </div>
                    <div class="tournament--mobile__container__content">
                        <div class="tournament--mobile__container__content__status">
                            {{ tournament.state }}
                        </div>
                        <div class="tournament--mobile__container__content__title">
                            {{ tournament.name }}
                        </div>
                        <div class="tournament--mobile__container__content__icons">
                            <i
                                class="icon icon--color--light-2 icon--nano icon--sport-nba m--r--1"
                            ></i>
                            <i
                                class="icon icon--color--light-2 icon--nano icon--sport-nfl m--r--1"
                            ></i>
                            <i
                                class="icon icon--color--light-2 icon--nano icon--sport-tennis m--r--1"
                            ></i>
                            <i
                                class="icon icon--color--light-2 icon--nano icon--sport-hockey m--r--1"
                            ></i>
                            <i
                                class="icon icon--color--light-2 icon--nano icon--sport-boxing m--r--1"
                            ></i>
                            <i
                                class="icon icon--color--light-2 icon--nano icon--sport-esports m--r--1"
                            ></i>
                            <i
                                class="icon icon--color--light-2 icon--nano icon--sport-baseball m--r--1"
                            ></i>
                        </div>
                        <div class="tournament--mobile__container__content__details">
                            <div class="tournament--mobile__container__content__details__item">
                                <i class="icon icon--people icon--micro"></i>
                                {{ tournament.players.length }}
                            </div>
                            <div class="tournament--mobile__container__content__details__item">
                                <i class="icon icon--calendar icon--micro"></i>
                                {{ tournament.timeFrame }}
                            </div>
                        </div>
                        <div class="tournament--mobile__container__content__prices">
                            <div class="tournament--mobile__container__content__prices__item">
                                <div
                                    class="tournament--mobile__container__content__prices__item__label"
                                >
                                    BUY-IN
                                </div>
                                <div
                                    class="tournament--mobile__container__content__prices__item__price"
                                >
                                    {{ tournament.buyIn | formatDollars }}
                                </div>
                            </div>
                            <div class="tournament--mobile__container__content__prices__item">
                                <div
                                    class="tournament--mobile__container__content__prices__item__label"
                                >
                                    PRIZE POOL
                                </div>
                                <div
                                    class="tournament--mobile__container__content__prices__item__price"
                                >
                                    $10,000
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tournament--mobile__buttons">
                    <div class="tournament--mobile__buttons__button">
                        <i class="icon icon--micro icon--games m--r--2"></i>
                        GAMES
                    </div>
                    <div class="tournament--mobile__buttons__button">
                        <i class="icon icon--micro icon--rank m--r--2"></i>
                        RANKS
                    </div>
                </div>

                <RegisterNowButton className="tournament--mobile__offer" :tournament="tournament" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import { Tournament } from "../../types/tournament";
import TableNoRecords from "../../../general/components/TableNoRecords.vue";
import { UserPlayer } from "../../../general/types/user";
import RegisterNowButton from "../../components/RegisterNowButton.vue";

export default Vue.extend({
    name: "TournamentList",
    components: { TableNoRecords, RegisterNowButton },
    props: {
        selectedTournamentId: Number,
    },

    computed: {
        filteredTournaments(): Tournament[] {
            return this.$stock.getters["tournamentList/filteredTournaments"];
        },

        isLoading(): boolean {
            return this.$stock.state.tournamentList.isLoading;
        },

        isFailed(): boolean {
            return this.$stock.state.tournamentList.isFailed;
        },
    },

    methods: {
        load() {
            this.$stock.dispatch("tournamentList/reload");
        },

        getSportsNames(sportsIds: string[]): string {
            const dict: ReadonlyMap<string, string> = this.$stock.getters["sport/sportDictionary"];
            return sportsIds.map(sportId => dict.get(sportId) ?? sportId).join(", ");
        },

        isSelected(tournament: Tournament): boolean {
            return tournament.id === this.selectedTournamentId;
        },

        isRegistered(tournament: Tournament): boolean {
            const playersDict: ReadonlyMap<number, UserPlayer> = this.$stock.getters[
                "user/playersDictByTournament"
            ];
            return playersDict.has(tournament.id);
        },

        selectTournament(tournament: Tournament): void {
            this.$emit("select", tournament.id);
        },

        openTournament(tournament: Tournament): void {
            this.$stock.commit("window/openWindow", tournament.id);
            this.$router.push(`/tournaments/${tournament.id}`);
        },
    },
});
</script>
