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
                    >
                        <td class="td col-start">
                            {{ tournament.starts }}
                        </td>
                        <td class="td col-sports">
                            {{ getSportsNames(tournament.sport_ids) }}
                        </td>
                        <td class="tdcol-buy-in">
                            {{ tournament.buy_in }}
                        </td>
                        <td class="td col-name">{{ tournament.name }}</td>
                        <td class="td col-time-frame">
                            {{ tournament.time_frame }}
                        </td>
                        <td class="td col-status">
                            {{ tournament.state }}
                        </td>
                        <td class="td col-enrolled">
                            {{ tournament.enrolled }}
                        </td>
                        <td class="td col-players">
                            0
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

export default Vue.extend({
    name: "TournamentList",
    components: { LoadingOverlay, TableNoRecords },
    props: {
        selectedTournamentId: Number,
    },

    computed: {
        filteredTournaments(): Tournament[] {
            return this.$store.getters["tournamentList/filteredTournaments"];
        },

        isLoading(): boolean {
            return this.$store.state.tournamentList.isLoading;
        },

        isFailed(): boolean {
            return this.$store.state.tournamentList.isFailed;
        },
    },

    methods: {
        load() {
            this.$store.dispatch("tournamentList/load");
        },

        getSportsNames(sportsIds: number[]): string {
            const dict: ReadonlyMap<number, string> = this.$store.getters["sport/sportDictionary"];
            return sportsIds.map(sportId => dict.get(sportId) ?? sportId).join(", ");
        },

        isSelected(tournament: Tournament): boolean {
            return tournament.id === this.selectedTournamentId;
        },

        selectTournament(tournament: Tournament): void {
            this.$emit("select", tournament.id);
        },

        openTournament(tournament: Tournament): void {
            this.$store.commit("tabs/openTab", tournament.id);
            this.$router.push(`/tournaments/${tournament.id}`);
        },
    },
});
</script>
