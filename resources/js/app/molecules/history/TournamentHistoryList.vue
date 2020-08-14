<template>
    <!-- <LoadingOverlay :loading="isLoading" :failed="isFailed" @retry="load">
        <div id="table-frm" class="table-frm">
            <table id="tournaments" class="table table-fixed">
                <thead class="thead">
                    <tr class="tr">
                        <th class="th col-start" scope="col">Start</th>
                        <th class="th col-sports" scope="col">Sports</th>
                        <th class="th col-buy-in" scope="col">Buy-In</th>
                        <th class="th col-name" scope="col">Tournament name</th>
                        <th class="th col-time-frame" scope="col">
                            Time Frame
                        </th>
                        <th class="th col-status" scope="col">Status</th>
                        <th class="th col-enrolled" scope="col">Enrolled</th>
                        <th class="th col-players" scope="col">Players</th>
                    </tr>
                </thead>
                <tbody class="tbody">
                    <tr
                        class="tr"
                        :class="{ selected: isSelected(tournament) }"
                        @click="selectTournament(tournament)"
                        @dblclick="openTournament(tournament)"
                        v-for="tournament in filteredTournaments"
                        :key="tournament.id"
                    >
                        <td class="td col-start">
                            {{ tournament.starts | toDateTime }}
                        </td>
                        <td class="td col-sports">
                            {{ getSportsNames(tournament.sportIds) }}
                        </td>
                        <td class="tdcol-buy-in">
                            {{ tournament.buyIn | formatDollars }}
                        </td>
                        <td class="td col-name">
                            <span
                                v-if="isRegistered(tournament)"
                                title="You're registered for this tournament"
                            >
                                <strong>{{ tournament.name }}</strong>
                                <i class="fas fa-check-circle"></i>
                            </span>

                            <span v-else>{{ tournament.name }}</span>
                        </td>
                        <td class="td col-time-frame">
                            {{ tournament.timeFrame }}
                        </td>
                        <td class="td col-status">
                            {{ tournament.state }}
                        </td>
                        <td class="td col-enrolled">
                            {{ tournament.players.length }}
                        </td>
                        <td class="td col-players">
                            {{ tournament.players.length }}
                        </td>
                    </tr>

                    <TableNoRecords v-if="!filteredTournaments.length" />
                </tbody>
            </table>
        </div>
    </LoadingOverlay> -->
    <div
        class="layout__content__container__content__container layout__content__container__content__container--paddingless-mobile"
    >
        <div class="d--flex j--between a--center m--b--4 d--only--desktop">
            <div class="form__control">
                <div class="form__control__icon form__control__icon--left">
                    <i class="icon icon--search icon--micro"></i>
                </div>
                <input class="input input--padding--left" placeholder="Search.." />
            </div>
            <div class="d--inline-flex a--center">
                <label class="label m--r--2 m--b--0">SORT</label>
                <div class="dropdown">
                    <div class="form__control">
                        <input
                            class="input"
                            type="text"
                            value="Name: Ascending"
                            readonly="readonly"
                        />
                        <div class="form__control__icon--right">
                            <i class="icon icon--micro icon--down"></i>
                        </div>
                    </div>
                    <div class="dropdown__content">
                        <div class="dropdown__content__item">
                            Name: Ascending
                            <i class="icon icon--smaller icon--check icon--color--yellow-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table d--only--desktop">
            <div class="table__header">
                <div class="table__header__label">START</div>
                <div class="table__header__label">TOURNAMENT NAME</div>
                <div class="table__header__label text--right">BUY-IN</div>
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
                <div class="tournament--desktop__item">{{ tournament.name }}</div>
                <div class="tournament--desktop__item text--right">
                    {{ tournament.buyIn | formatDollars }}
                </div>
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
                            <div class="tournament--mobile__container__sidebar__date__day">24</div>
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
                                    {{ theTournament.prizePoolMoney | formatDollars }}
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
                    <div class="tournament--mobile__buttons__button">
                        <i class="icon icon--micro icon--cup m--r--2"></i>
                        PRIZES
                    </div>
                </div>
                <div class="tournament--mobile__slip">
                    <div class="tournament--mobile__slip__item">
                        <i class="icon icon--slip icon--color--light-1 m--r--2"></i>
                        BET SLIP
                    </div>
                    <div class="tournament--mobile__slip__item">
                        <span class="tag tag--color--red tag--medium">2</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import { Tournament } from "../../types/tournament";
import LoadingOverlay from "../../../general/components/LoadingOverlay";
import TableNoRecords from "../../../general/components/TableNoRecords.vue";
import { UserPlayer } from "../../../general/types/user";

export default Vue.extend({
    name: "TournamentHistoryList",
    components: { LoadingOverlay, TableNoRecords },
    props: {
        selectedTournamentId: Number,
    },

    computed: {
        filteredTournaments(): Tournament[] {
            return this.$stock.state.tournamentHistoryList.tournaments;
        },

        isLoading(): boolean {
            return this.$stock.state.tournamentHistoryList.isLoading;
        },

        isFailed(): boolean {
            return this.$stock.state.tournamentHistoryList.isFailed;
        },
    },

    methods: {
        load() {
            this.$stock.dispatch("tournamentHistoryList/reload");
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
