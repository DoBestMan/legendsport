<template>
    <div class="footer-frm">
        <button
            v-if="isRegistered"
            class="btn btn-action btn-place-bet"
            @click="placeBet"
            :disabled="disabled"
        >
            Place Bet
        </button>

        <RegisterNowButton v-else class="btn-place-bet" :tournament="tournament" />
    </div>
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
