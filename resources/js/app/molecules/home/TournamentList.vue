<template>
    <LoadingOverlay
        :loading="tournamentListStore.isLoading"
        :failed="tournamentListStore.hasFailed"
        @retry="tournamentListStore.load"
    >
        <div id="table-frm">
            <table id="tournaments" class="table headerFixed">
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
                    <template v-for="tournament in tournamentListStore.filteredTournaments">
                        <tr class="tr" :class="{ selected: tournament.selected == true }">
                            <td class="td col-start">
                                {{ tournament.starts }}
                            </td>
                            <td class="td col-sports">
                                <template v-for="id in tournament.sport_id">
                                    {{ getSportName(id) }}
                                </template>
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
                    </template>
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

export default Vue.extend({
    name: "TournamentList",
    components: { LoadingOverlay },

    created() {
        tournamentListStore.load();
    },

    computed: {
        tournamentListStore() {
            return tournamentListStore;
        },
    },

    methods: {
        getSportName(sportId: number): string {
            return getSportName(sportId);
        },
    },
});
</script>
