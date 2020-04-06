<template>
    <LoadingOverlay :loading="isLoading" :failed="isFailed" @retry="load">
        <div id="table-frm">
            <table id="tournaments" class="table table-fixed table-full">
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
                            {{ tournament.buyIn }}
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
    </LoadingOverlay>
</template>

<script lang="ts">
import Vue from "vue";
import { Tournament } from "../../types/tournament";
import LoadingOverlay from "../../../general/components/LoadingOverlay";
import TableNoRecords from "../../../general/components/TableNoRecords.vue";
import { UserPlayer } from "../../../general/types/user";

export default Vue.extend({
    name: "TournamentList",
    components: { LoadingOverlay, TableNoRecords },
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

        getSportsNames(sportsIds: number[]): string {
            const dict: ReadonlyMap<number, string> = this.$stock.getters["sport/sportDictionary"];
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
