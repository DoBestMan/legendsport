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
                        <div class="team">{{ game.teamHome }}</div>
                        <div class="score">{{ getGameScoreHome(game) }}</div>
                        <div class="vs">@</div>
                        <div class="team">{{ game.teamAway }}</div>
                        <div class="score">{{ getGameScoreAway(game) }}</div>
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
import { Result } from "../../types/result";
import { getScoreAway, getScoreHome } from "../../utils/game/match";

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

    computed: {
        resultDict(): ReadonlyMap<string, Result> {
            return this.$stock.getters["result/resultDictionary"];
        },
    },

    methods: {
        getStartsAt(game: Game): string {
            return moment(game.startsAt).format("MMM, DD");
        },

        getSportName(game: Game): string {
            const dict: ReadonlyMap<string, string> = this.$stock.getters["sport/sportDictionary"];
            return dict.get(game.sportId) ?? String(game.sportId);
        },

        getGameScoreHome(game: Game): number {
            return getScoreHome(game, this.resultDict);
        },

        getGameScoreAway(game: Game): number {
            return getScoreAway(game, this.resultDict);
        },

        selectGame(game: Game) {
            this.selectedGameId = game.id;
        },
    },
});
</script>
