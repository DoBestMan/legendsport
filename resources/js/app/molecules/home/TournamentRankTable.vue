<template>
    <table id="rank" class="table table-fixed">
        <thead class="thead">
            <tr class="tr">
                <th id="col-position" class="th" scope="col">#</th>
                <th id="col-player" class="th" scope="col">Players</th>
                <th id="col-price" class="th" scope="col">Price</th>
            </tr>
        </thead>
        <tbody class="tbody">
            <template v-for="i in 50">
                <tr class="tr" :class="{ selected: i === selectedUser }" @click="selectedUser = i">
                    <td class="td col-position">{{ i }}</td>
                    <td class="td col-player">
                        <div class="img-frm">
                            <i class="icon fas fa-user-circle"></i>

                            <div class="img"></div>
                        </div>
                        Player name
                    </td>
                    <td class="td">1,000</td>
                </tr>
            </template>
        </tbody>
    </table>
</template>

<script lang="ts">
import Vue from "vue";

export default Vue.extend({
    name: "TournamentRankTable",

    data() {
        return {
            selectedUser: null,
        };
    },
});
</script>
