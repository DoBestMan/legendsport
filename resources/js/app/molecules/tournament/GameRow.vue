<template>
    <div style="display: contents">
        <tr class="tr">
            <td class="td col-datetime">{{ game.home_team }}</td>

            <td class="td col-money">
                <button type="button" class="button">{{ moneyLineHome }}</button>
            </td>

            <td class="td col-spread">
                <button type="button" class="button">
                    {{ pointSpreadHomeLine }}<br />{{ pointSpreadHome }}
                </button>
            </td>

            <td class="td col-spread">
                <button type="button" class="button">{{ underLine }}<br />{{ totalNumber }}</button>
            </td>
        </tr>

        <tr class="tr">
            <td class="td col-datetime">{{ game.away_team }}</td>

            <td class="td col-money">
                <button type="button" class="button">{{ moneyLineAway }}</button>
            </td>

            <td class="td col-spread">
                <button type="button" class="button">
                    {{ pointSpreadAwayLine }}<br />{{ pointSpreadAway }}
                </button>
            </td>

            <td class="td col-spread">
                <button type="button" class="button">{{ overLine }}<br />{{ totalNumber }}</button>
            </td>
        </tr>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { Game } from "../../types/game";
import { Odd } from "../../../general/types/odd";

export default Vue.extend({
    name: "GameRow",
    props: {
        game: Object as PropType<Game>,
    },

    computed: {
        odd(): Odd | null {
            const dict: ReadonlyMap<string, Odd> = this.$store.getters["odd/oddDictionary"];
            return dict.get(this.game.event_id) ?? null;
        },

        moneyLineHome(): string {
            return this.odd?.money_line_home ?? "n/a";
        },

        moneyLineAway(): string {
            return this.odd?.money_line_away ?? "n/a";
        },

        pointSpreadHome(): string {
            return this.odd?.point_spread_home ?? "n/a";
        },

        pointSpreadAway(): string {
            return this.odd?.point_spread_away ?? "n/a";
        },

        pointSpreadHomeLine(): string {
            return this.odd?.point_spread_home_line ?? "n/a";
        },

        pointSpreadAwayLine(): string {
            return this.odd?.point_spread_away_line ?? "n/a";
        },

        overLine(): string {
            return this.odd?.overline ?? "n/a";
        },

        underLine(): string {
            return this.odd?.underline ?? "n/a";
        },

        totalNumber(): string {
            return this.odd?.total_number ?? "n/a";
        },
    },
});
</script>
