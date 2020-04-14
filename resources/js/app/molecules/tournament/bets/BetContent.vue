<template>
    <div>
        <div class="text">{{ startsAt | toDateTime }}</div>
        <div class="text game-frm">
            <div class="text team">{{ teamHome }}</div>
            <div class="text score">{{ scoreHome }}</div>
            <div class="text vs">@</div>
            <div class="text team">{{ teamAway }}</div>
            <div class="text score">{{ scoreAway }}</div>
        </div>
        <div class="text">{{ selectedTeam }} / {{ odd | formatOdd }}</div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";

// TODO Display status: WIN, LOSS or PUSH

export default Vue.extend({
    name: "BetContent",
    props: {
        odd: Number,
        scoreAway: Number,
        scoreHome: Number,
        selectedTeam: String,
        startsAt: String,
        teamAway: String,
        teamHome: String,
    },
});
</script>
