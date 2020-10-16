<template>
    <div v-if="tournament.state === TournamentState.Completed">
        <div class="rank" v-for="(prize, index) in prizes" :key="index" @click="selectPrize(prize)">
            <div class="rank__content">
                <div class="rank__content__order">
                    {{ prize.position }}
                </div>
                <div class="rank__content__user">
                    <div class="rank__content__user__avatar">
                        <i class="icon icon--person icon--micro"></i>
                    </div>
                    <div class="rank__content__user__name">
                        {{ prize.usernames }}
                    </div>
                </div>
            </div>
            <div class="rank__content">
                <div class="rank__content__coins">
                    <i class="icon icon--atom icon--coins icon--color--yellow-2 m--r--1"></i>
                    {{ prize.prize | formatCurrency }}
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import TableNoRecords from "../../../../general/components/TableNoRecords.vue";
import { Prize, Tournament } from "../../../types/tournament";
import { TournamentState } from "../../../../general/types/tournament";
import { Nullable } from "../../../../general/types/types";
import { Player } from "../../../types/player";

export default Vue.extend({
    name: "PrizePool",
    components: { TableNoRecords },

    props: {
        tournament: Object as PropType<Tournament>,
    },

    data() {
        return {
            selectedPrize: null as Nullable<number>,
        };
    },

    computed: {
        players(): Player[] {
            return this.tournament?.players ?? [];
        },

        TournamentState() {
            return TournamentState;
        },

        prizes(): any[] {
            const output = [];
            let lastMaxPosition = 0;

            for (const prize of this.tournament.prizePool) {
                const minPosition = lastMaxPosition + 1;
                const maxPosition = prize.maxPosition;
                const position =
                    minPosition === maxPosition ? minPosition : `${minPosition}-${maxPosition}`;
                let usernames = "";
                if (minPosition === maxPosition) {
                    usernames = this.players[minPosition - 1].name;
                } else {
                    for (let i = minPosition; i <= maxPosition; i++) {
                        usernames = usernames.concat(this.players[i - 1].name);
                    }
                }

                output.push({
                    maxPosition,
                    position,
                    prize: prize.prize,
                    usernames,
                });

                lastMaxPosition = maxPosition;
            }

            return output;
        },
    },

    methods: {
        selectPrize(prize: Prize) {
            this.selectedPrize = prize.maxPosition;
        },
    },
});
</script>
