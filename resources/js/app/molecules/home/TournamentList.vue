<template>
    <LoadingOverlay
        :loading="tournamentListStore.isLoading"
        :failed="tournamentListStore.hasFailed"
        @retry="tournamentListStore.load"
    >
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
                        v-for="tournament in tournamentListStore.filteredTournaments"
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

                    <tr v-if="!tournamentListStore.filteredTournaments.length">
                        <td colspan="10" class="p-4 text-center w-100">
                            No records
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </LoadingOverlay>
</template>

<script lang="ts">
import Vue from "vue";
import { getSportName } from "../../../general/utils/sportUtils";
import LoadingOverlay from "../../../general/components/LoadingOverlay";
import tournamentListStore from "../../stores/tournamentListStore";
import { Tournament } from "../../types/tournament";

export default Vue.extend({
    name: "TournamentList",
    components: { LoadingOverlay },
    props: {
        selectedTournamentId: Number,
    },

    created() {
        tournamentListStore.load();
    },

    computed: {
        tournamentListStore() {
            return tournamentListStore;
        },
    },

    methods: {
        getSportsNames(sportsIds: number[]): string {
            return sportsIds.map(getSportName).join(", ");
        },

        isSelected(tournament: Tournament): boolean {
            return tournament.id === this.selectedTournamentId;
        },

        selectTournament(tournament: Tournament) {
            this.$emit("select", tournament.id);
        },
    },
});
</script>
