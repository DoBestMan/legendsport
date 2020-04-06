<template>
    <div>
        <div class="text">{{ matchTime | toDateTime }}</div>
        <div class="text game-frm">
            <div class="text team">{{ homeTeam }}</div>
            <div class="text score">0</div>
            <div class="text vs">@</div>
            <div class="text team">{{ awayTeam }}</div>
            <div class="text score">0</div>
        </div>
        <div class="text">{{ selectedTeam }} / {{ odd | formatOdd }}</div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";

export default Vue.extend({
    name: "BetContent",
    props: {
        matchTime: String,
        homeTeam: String,
        awayTeam: String,
        selectedTeam: String,
        odd: Number,
    },
});
</script>
