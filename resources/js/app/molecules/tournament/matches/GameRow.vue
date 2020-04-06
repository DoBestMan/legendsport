<template>
    <div style="display: contents">
        <tr class="tr">
            <td class="td col-datetime">{{ game.home_team }}</td>

            <td class="td col-money">
                <button
                    v-if="Number(moneyLineHome)"
                    type="button"
                    class="btn"
                    :class="{ checked: selectedMoneyLineHome }"
                    @click="emitToggleOdd(PendingOddType.MoneyLineHome)"
                >
                    {{ moneyLineHome | formatOdd }}
                </button>
                <DisabledButton v-else />
            </td>

            <td class="td col-spread">
                <button
                    v-if="Number(pointSpreadHomeLine)"
                    type="button"
                    class="btn"
                    :class="{ checked: selectedSpreadHome }"
                    @click="emitToggleOdd(PendingOddType.SpreadHome)"
                >
                    {{ pointSpreadHomeLine }}<br />{{ pointSpreadHome }}
                </button>
                <DisabledButton v-else />
            </td>

            <td class="td col-spread">
                <button
                    v-if="Number(totalNumber)"
                    type="button"
                    class="btn"
                    :class="{ checked: selectedTotalUnder }"
                    @click="emitToggleOdd(PendingOddType.TotalUnder)"
                >
                    U {{ totalNumber }}<br />{{ underLine | formatOdd }}
                </button>
                <DisabledButton v-else />
            </td>
        </tr>

        <tr class="tr">
            <td class="td col-datetime">{{ game.away_team }}</td>

            <td class="td col-money">
                <button
                    v-if="Number(moneyLineAway)"
                    type="button"
                    class="btn"
                    :class="{ checked: selectedMoneyLineAway }"
                    @click="emitToggleOdd(PendingOddType.MoneyLineAway)"
                >
                    {{ moneyLineAway | formatOdd }}
                </button>
                <DisabledButton v-else />
            </td>

            <td class="td col-spread">
                <button
                    v-if="Number(pointSpreadAwayLine)"
                    type="button"
                    class="btn"
                    :class="{ checked: selectedSpreadAway }"
                    @click="emitToggleOdd(PendingOddType.SpreadAway)"
                >
                    {{ pointSpreadAwayLine }}<br />{{ pointSpreadAway }}
                </button>
                <DisabledButton v-else />
            </td>

            <td class="td col-spread">
                <button
                    v-if="Number(totalNumber)"
                    type="button"
                    class="btn"
                    :class="{ checked: selectedTotalOver }"
                    @click="emitToggleOdd(PendingOddType.TotalOver)"
                >
                    O {{ totalNumber }}<br />{{ overLine | formatOdd }}
                </button>
                <DisabledButton v-else />
            </td>
        </tr>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { Game } from "../../../types/game";
import { Odd } from "../../../../general/types/odd";
import { PendingOdd, PendingOddType, Window } from "../../../types/window";
import { DeepReadonly } from "../../../../general/types/types";
import DisabledButton from "./DisabledButton.vue";

const createPendingOddKey = (pendingOdd: Pick<PendingOdd, "eventId" | "type">): string =>
    `${pendingOdd.eventId}#${pendingOdd.type}`;

export default Vue.extend({
    name: "GameRow",
    components: { DisabledButton },

    props: {
        window: Object as PropType<DeepReadonly<Window>>,
        game: Object as PropType<DeepReadonly<Game>>,
    },

    computed: {
        odd(): Odd | null {
            const dict: ReadonlyMap<string, Odd> = this.$stock.getters["odd/oddDictionary"];
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

        pendingOddsDictionary(): ReadonlyMap<string, PendingOdd> {
            return new Map(
                this.window.pendingOdds.map(pendingOdd => [
                    createPendingOddKey(pendingOdd),
                    pendingOdd,
                ]),
            );
        },

        selectedMoneyLineHome(): boolean {
            return this.pendingOddsDictionary.has(
                createPendingOddKey({
                    eventId: this.game.event_id,
                    type: PendingOddType.MoneyLineHome,
                }),
            );
        },

        selectedMoneyLineAway(): boolean {
            return this.pendingOddsDictionary.has(
                createPendingOddKey({
                    eventId: this.game.event_id,
                    type: PendingOddType.MoneyLineAway,
                }),
            );
        },

        selectedSpreadHome(): boolean {
            return this.pendingOddsDictionary.has(
                createPendingOddKey({
                    eventId: this.game.event_id,
                    type: PendingOddType.SpreadHome,
                }),
            );
        },

        selectedSpreadAway(): boolean {
            return this.pendingOddsDictionary.has(
                createPendingOddKey({
                    eventId: this.game.event_id,
                    type: PendingOddType.SpreadAway,
                }),
            );
        },

        selectedTotalUnder(): boolean {
            return this.pendingOddsDictionary.has(
                createPendingOddKey({
                    eventId: this.game.event_id,
                    type: PendingOddType.TotalUnder,
                }),
            );
        },

        selectedTotalOver(): boolean {
            return this.pendingOddsDictionary.has(
                createPendingOddKey({
                    eventId: this.game.event_id,
                    type: PendingOddType.TotalOver,
                }),
            );
        },

        PendingOddType(): typeof PendingOddType {
            return PendingOddType;
        },
    },

    methods: {
        emitToggleOdd(type: PendingOddType) {
            const payload: PendingOdd = {
                tournamentEventId: this.game.id,
                eventId: this.game.event_id,
                type,
            };
            this.$emit("toggleOdd", payload);
        },
    },
});
</script>
