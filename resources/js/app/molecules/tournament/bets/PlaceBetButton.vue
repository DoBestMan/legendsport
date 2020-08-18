<template>
    <span style="width: 230px;">
        <button
            class="button button--large m--l--4 m--b--0"
            v-if="isRegistered"
            @click="placeBet"
            :disabled="disabled"
        >
            PLACE BET
        </button>

        <RegisterNowButton
            v-else
            class="button button--large m--l--4 m--b--0"
            :tournament="tournament"
        />
    </span>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import RegisterNowButton from "../../../components/RegisterNowButton.vue";
import { Tournament } from "../../../types/tournament";
import { UserPlayer } from "../../../../general/types/user";

export default Vue.extend({
    name: "PlaceBetButton",
    components: { RegisterNowButton },

    props: {
        disabled: Boolean,
        tournament: Object as PropType<Tournament>,
    },

    computed: {
        isRegistered(): boolean {
            const playersDict: ReadonlyMap<number, UserPlayer> = this.$stock.getters[
                "user/playersDictByTournament"
            ];
            return playersDict.has(this.tournament.id);
        },
    },

    methods: {
        placeBet() {
            this.$emit("placeBet");
        },
    },
});
</script>
