<template>
    <table id="games" class="table">
        <thead class="thead">
            <tr class="tr">
                <th id="col-time" class="th" scope="col">Time</th>
                <th id="col-sport" class="th" scope="col">Sport</th>
                <th id="col-game" class="th" scope="col">Game</th>
            </tr>
        </thead>
        <tbody class="tbody">
            <tr class="tr" v-for="game in games">
                <td class="td col-time">{{ getMatchTime(game) }}</td>
                <td class="td col-sport">{{ getSportName(game) }}</td>
                <td class="td col-game">
                    <div class="team">{{ game.home_team }}</div>
                    <div class="score">0</div>
                    <div class="vs">@</div>
                    <div class="team">{{ game.away_team }}</div>
                    <div class="score">0</div>
                </td>
            </tr>
            <TableNoRecords v-if="!games.length" />
        </tbody>
    </table>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import moment from "moment";
import { getSportName } from "../../../general/utils/sportUtils";
import { Game } from "../../types/game";
import TableNoRecords from "../../../general/components/TableNoRecords.vue";

export default Vue.extend({
    name: "TournamentGamesTable",
    components: { TableNoRecords },

    props: {
        games: Array as PropType<Game[]>,
    },

    methods: {
        getMatchTime(game: any): string {
            return moment(game.match_time).format("MMM, DD");
        },

        getSportName(game: any): string {
            return getSportName(game.sport_id);
        },
    },
});
</script>
