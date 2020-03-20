<template>
    <table class="table table-fixed rank">
        <thead class="thead">
            <tr class="tr">
                <th class="th col-position" scope="col">Rank</th>
                <th class="th col-player" scope="col">Players</th>
                <th class="th col-price" scope="col">Price</th>
            </tr>
        </thead>
        <tbody class="tbody">
            <tr
                class="tr"
                :class="{ selected: player.id === selectedPlayerId }"
                @click="selectPlayer(player)"
                v-for="player in players"
            >
                <td class="td col-position">{{ player.id }}</td>
                <td class="td col-player">
                    <div class="img-frm">
                        <i class="icon fas fa-user-circle"></i>
                        <div class="img"></div>
                    </div>
                    n/a
                </td>
                <td class="td">n/a</td>
            </tr>
            <TableNoRecords v-if="!players.length" />
        </tbody>
    </table>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { Player } from "../../types/player";
import { Nullable } from "../../../general/types/types";
import TableNoRecords from "../../../general/components/TableNoRecords.vue";

export default Vue.extend({
    name: "TournamentRankTable",
    components: { TableNoRecords },

    props: {
        players: Array as PropType<Player[]>,
    },

    data() {
        return {
            selectedPlayerId: null as Nullable<number>,
        };
    },

    methods: {
        selectPlayer(player: Player) {
            this.selectedPlayerId = player.id;
        },
    },
});
</script>
