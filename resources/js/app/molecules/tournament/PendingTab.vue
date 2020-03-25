<template>
    <div class="tab-content-frm">
        <div class="items-frm">
            <div class="event-frm">
                <div class="data-frm">
                    <div class="type-bet">Straight</div>

                    <div class="text">[datetime]</div>
                    <div class="text game-frm">
                        <div class="text team">Miami Dolphins</div>
                        <div class="text score">0</div>
                        <div class="text vs">@</div>
                        <div class="text team">Washington Nationals</div>
                        <div class="text score">0</div>
                    </div>
                    <div class="text">[team/+odds/result]</div>
                </div>

                <div class="bet-frm">
                    <div>Bet: $ 0</div>

                    <div>Win: $ 0</div>
                </div>
            </div>
            <div class="event-frm">
                <div class="data-frm">
                    <div class="type-bet">Parlay</div>

                    <div class="text">[datetime]</div>
                    <div class="text game-frm">
                        <div class="text team">Team C</div>
                        <div class="text score">0</div>
                        <div class="text vs">@</div>
                        <div class="text team">Team D</div>
                        <div class="text score">0</div>
                    </div>
                    <div class="text">[team/+odds/result]</div>
                </div>

                <div class="data-frm">
                    <div class="text">[datetime]</div>
                    <div class="text game-frm">
                        <div class="text team">Team C</div>
                        <div class="text score">0</div>
                        <div class="text vs">@</div>
                        <div class="text team">Team D</div>
                        <div class="text score">0</div>
                    </div>
                    <div class="text">[team/+odds/result]</div>
                </div>

                <div class="bet-frm">
                    <div>Bet: $0</div>

                    <div>Win: $0</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";

export default Vue.extend({
    name: "PendingTab",
});
</script>
