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
                v-for="(tournament, index) in filteredTournaments"
                :key="`${tournament.name}-desktop-${index}`"
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
                style="margin-bottom: 10px;"
                v-for="(tournament, index) in filteredTournaments"
                :key="`${tournament.name}-mobile-${index}`"
                @click="openMobileTournament(tournament)"
            >
                <div class="tournament--mobile__container">
                    <div class="tournament--mobile__container__sidebar">
                        <div class="tournament--mobile__container__sidebar__date">
                            <div class="tournament--mobile__container__sidebar__date__weekday">
                                {{ getWeekday(tournament.starts) }}
                            </div>
                            <div class="tournament--mobile__container__sidebar__date__day">
                                {{ getDate(tournament.starts) }}
                            </div>
                            <div class="tournament--mobile__container__sidebar__date__month">
                                {{ getMonth(tournament.starts) }}
                            </div>
                        </div>
                        <div class="tournament--mobile__container__sidebar__time">
                            <div class="tournament--mobile__container__sidebar__time__hour">
                                {{ getTime(tournament.starts) }}
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
                            <span
                                v-if="isRegistered(tournament)"
                                title="You're registered for this tournament"
                            >
                                <strong>{{ tournament.name }}</strong>
                                <i class="icon icon--smaller icon--check icon--color--yellow-1" />
                            </span>
                            <span v-else>{{ tournament.name }}</span>
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
                                    {{ tournament.prizePoolMoney | formatDollars }}
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

                <RegisterNowButton
                    v-if="!isRegistered(tournament)"
                    className="tournament--mobile__offer"
                    style="width: 100%;"
                    :tournament="tournament"
                />
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
        getWeekday(day: Date): string {
            const newDate = new Date(day);
            const wd = newDate.getDay();
            let str_wd = "";
            switch (wd) {
                case 0:
                    str_wd = "SUN";
                    break;
                case 1:
                    str_wd = "MON";
                    break;
                case 2:
                    str_wd = "TUE";
                    break;
                case 3:
                    str_wd = "WED";
                    break;
                case 4:
                    str_wd = "THU";
                    break;
                case 5:
                    str_wd = "FRI";
                    break;
                case 6:
                    str_wd = "SAT";
                    break;
                default:
                    break;
            }
            return str_wd;
        },

        getDate(day: Date): number {
            const newDate = new Date(day);
            return newDate.getDate();
        },

        getMonth(day: Date): string {
            const newDate = new Date(day);
            const wd = newDate.getMonth();
            let str_month = "";
            switch (wd) {
                case 0:
                    str_month = "JAN";
                    break;
                case 1:
                    str_month = "FEB";
                    break;
                case 2:
                    str_month = "MAR";
                    break;
                case 3:
                    str_month = "APR";
                    break;
                case 4:
                    str_month = "MAY";
                    break;
                case 5:
                    str_month = "JUN";
                    break;
                case 6:
                    str_month = "JUL";
                    break;
                case 7:
                    str_month = "AUG";
                    break;
                case 8:
                    str_month = "SEP";
                    break;
                case 9:
                    str_month = "OCT";
                    break;
                case 10:
                    str_month = "NOV";
                    break;
                case 10:
                    str_month = "DEC";
                    break;
                default:
                    break;
            }
            return str_month;
        },

        getTime(day: Date): string {
            const newDate = new Date(day);
            return newDate.getHours() + "." + newDate.getMinutes();
        },

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

        openMobileTournament(tournament: Tournament): void {
            this.$stock.commit("window/openWindow", tournament.id);
            this.$router.push(`/tour_info/${tournament.id}`);
        },
    },
});
</script>
