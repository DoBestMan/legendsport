<template>
    <div class="bet__footer__line__detail">
        <button
            class="button--large--bet"
            v-if="isRegistered"
            @click="placeBet"
            :disabled="disabled"
        >
            PLACE BET
        </button>

        <RegisterNowButton v-else class="button--large--bet" :tournament="tournament" />
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
            console.log("asdfwoejrowejrowjerowjero");
            this.$emit("placeBet");
        },
    },
});
</script>
