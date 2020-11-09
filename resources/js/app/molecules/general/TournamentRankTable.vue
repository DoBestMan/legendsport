<template>
    <div>
        <div
            class="rank"
            v-for="(player, index) in players"
            :key="player.id"
            :class="[{ 'rank-player-selected': player.id == selectedPlayerId }, 'rank-hover']"
            @click="selectPlayer(player)"
        >
            <div class="rank__content">
                <div class="rank__content__order">
                    {{ index + 1 }}
                </div>
                <div class="rank__content__user">
                    <div class="rank__content__user__avatar">
                        <i class="icon icon--person icon--micro"></i>
                    </div>
                    <div class="rank__content__user__name" @click="selectPlayer(player)">
                        {{ player.name }}
                    </div>
                </div>
            </div>
            <div class="rank__content">
                <div class="rank__content__coins">
                    <i class="icon icon--atom icon--coins icon--color--yellow-2 m--r--1"></i>
                    {{ player.chips | formatChip }}
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { AxiosError } from "axios";
import { Player } from "../../types/player";
import { TournamentPlayer } from "../../../general/types/user";
import { Nullable } from "../../../general/types/types";
import TableNoRecords from "../../../general/components/TableNoRecords.vue";

export default Vue.extend({
    name: "TournamentRankTable",
    components: { TableNoRecords },

    props: {
        players: Array as PropType<Player[]>,
        tournamentId: Number,
    },

    data() {
        return {
            selectedPlayerId: null as Nullable<number>,
            player: null as Nullable<TournamentPlayer>,
        };
    },

    methods: {
        async selectPlayer(player: Player) {
            if (this.selectedPlayerId === player.id) {
                return;
            }
            this.selectedPlayerId = player.id;
            const user = this.$store.state.user.user;
            if (!user) return;
            if (user.name != player.name) {
                try {
                    this.player = await this.$stock.state.api.getTournamentPlayer(
                        this.tournamentId,
                        player.id,
                    );
                } catch (e) {
                    this.$toast.error((e as AxiosError).response?.data.message);
                } finally {
                    this.$stock.commit("playerBetInfo/markPlayerBetInfoSelection", {
                        player: this.player,
                    });
                }
            } else {
                this.$stock.commit("playerBetInfo/resetPlayerBetSelection");
            }
        },
    },
});
</script>

<style>
.table-rank-frm {
    min-height: 110px;
}
.rank-player-selected {
    border: 2px solid #f6e02f;
}
.rank-hover {
    cursor: pointer;
}
</style>
