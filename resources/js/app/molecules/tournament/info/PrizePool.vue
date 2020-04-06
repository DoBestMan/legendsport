<template>
    <table class="awards table">
        <thead class="thead">
            <tr class="tr">
                <th class="th col-position" scope="col">Position</th>
                <th class="th col-prize" scope="col">Prize</th>
            </tr>
        </thead>
        <tbody class="tbody">
            <tr class="tr" v-for="prize in prizes">
                <td class="td col-position">{{ prize.position }}</td>
                <td class="td col-prize">{{ prize.prize | formatCurrency }}</td>
            </tr>
            <TableNoRecords v-if="!tournament.prizePool.length" />
        </tbody>
    </table>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import TableNoRecords from "../../../../general/components/TableNoRecords.vue";
import { Tournament } from "../../../types/tournament";

export default Vue.extend({
    name: "PrizePool",
    components: { TableNoRecords },
    props: {
        tournament: Object as PropType<Tournament>,
    },

    computed: {
        prizes(): any[] {
            const output = [];

            let lastMaxPosition = 0;
            for (const prize of this.tournament.prizePool) {
                const minPosition = lastMaxPosition + 1;
                const maxPosition = prize.maxPosition;
                output.push({
                    position:
                        minPosition === maxPosition ? minPosition : `${minPosition}-${maxPosition}`,
                    prize: prize.prize,
                });

                lastMaxPosition = maxPosition;
            }

            return output;
        },
    },
});
</script>
