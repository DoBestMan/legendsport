<template>
    <div class="table-frm">
        <table id="games" class="table table-fixed">
            <thead class="thead">
                <tr class="tr">
                    <th id="col-time" class="th" scope="col">Time</th>
                    <th id="col-sport" class="th" scope="col">Sport</th>
                    <th id="col-game" class="th" scope="col">Game</th>
                </tr>
            </thead>
            <tbody class="tbody">
                <tr
                    class="tr"
                    :class="{ selected: game.id === selectedGameId }"
                    @click="selectGame(game)"
                    v-for="game in games"
                    :key="game.id"
                >
                    <td class="td col-time">{{ getStartsAt(game) }}</td>
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
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import moment from "moment";
import { Game } from "../../types/game";
import TableNoRecords from "../../../general/components/TableNoRecords.vue";
import { Nullable } from "../../../general/types/types";

export default Vue.extend({
    name: "TournamentGamesTable",
    components: { TableNoRecords },

    props: {
        games: Array as PropType<Game[]>,
    },

    data() {
        return {
            selectedGameId: null as Nullable<number>,
        };
    },

    methods: {
        getStartsAt(game: Game): string {
            return moment(game.starts_at).format("MMM, DD");
        },

        getSportName(game: Game): string {
            const dict: ReadonlyMap<string, string> = this.$stock.getters["sport/sportDictionary"];
            return dict.get(game.sport_id) ?? String(game.sport_id);
        },

        selectGame(game: Game) {
            this.selectedGameId = game.id;
        },
    },
});
</script>
