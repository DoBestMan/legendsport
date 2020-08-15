<template>
    <div class="game__rank__container__scroll">
        <div class="rank" v-for="(player, index) in players" :key="player.id">
            <div class="rank__content">
                <div class="rank__content__order">
                    {{ index + 1 }}
                </div>
                <div class="rank__content__user">
                    <div class="rank__content__user__avatar">
                        <i class="icon icon--person icon--micro"></i>
                    </div>
                    <div class="rank__content__user__name">
                        {{ player.name }}
                    </div>
                </div>
            </div>
            <div class="rank__content">
                <div class="rank__content__coins">
                    <i class="icon icon--atom icon--coins icon--color--yellow-2 m--r--1"></i>
                    {{ player.balance | formatChip }}
                </div>
            </div>
        </div>
    </div>
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

<style>
.table-rank-frm {
    min-height: 110px;
}
</style>
