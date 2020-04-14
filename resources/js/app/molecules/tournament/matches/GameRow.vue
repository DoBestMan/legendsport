<template>
    <div style="display: contents">
        <tr class="tr">
            <td class="td col-datetime">{{ game.teamHome }}</td>

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
                    {{ pointSpreadHomeLine }}<br />{{ pointSpreadHome | formatOdd }}
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
            <td class="td col-datetime">{{ game.teamAway }}</td>

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
                    {{ pointSpreadAwayLine }}<br />{{ pointSpreadAway | formatOdd }}
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
import { PendingOdd, PendingOddType, Window } from "../../../types/window";
import { DeepReadonly } from "../../../../general/types/types";
import DisabledButton from "./DisabledButton.vue";
import { Odd } from "../../../types/odd";

const createPendingOddKey = (pendingOdd: Pick<PendingOdd, "externalId" | "type">): string =>
    `${pendingOdd.externalId}#${pendingOdd.type}`;

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
            return dict.get(this.game.externalId) ?? null;
        },

        moneyLineHome(): string {
            return String(this.odd?.money_line_home ?? "n/a");
        },

        moneyLineAway(): string {
            return String(this.odd?.money_line_away ?? "n/a");
        },

        pointSpreadHome(): string {
            return String(this.odd?.point_spread_home ?? "n/a");
        },

        pointSpreadAway(): string {
            return String(this.odd?.point_spread_away ?? "n/a");
        },

        pointSpreadHomeLine(): string {
            return this.odd?.point_spread_home_line ?? "n/a";
        },

        pointSpreadAwayLine(): string {
            return this.odd?.point_spread_away_line ?? "n/a";
        },

        overLine(): string {
            return String(this.odd?.overline ?? "n/a");
        },

        underLine(): string {
            return String(this.odd?.underline ?? "n/a");
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
                    externalId: this.game.externalId,
                    type: PendingOddType.MoneyLineHome,
                }),
            );
        },

        selectedMoneyLineAway(): boolean {
            return this.pendingOddsDictionary.has(
                createPendingOddKey({
                    externalId: this.game.externalId,
                    type: PendingOddType.MoneyLineAway,
                }),
            );
        },

        selectedSpreadHome(): boolean {
            return this.pendingOddsDictionary.has(
                createPendingOddKey({
                    externalId: this.game.externalId,
                    type: PendingOddType.SpreadHome,
                }),
            );
        },

        selectedSpreadAway(): boolean {
            return this.pendingOddsDictionary.has(
                createPendingOddKey({
                    externalId: this.game.externalId,
                    type: PendingOddType.SpreadAway,
                }),
            );
        },

        selectedTotalUnder(): boolean {
            return this.pendingOddsDictionary.has(
                createPendingOddKey({
                    externalId: this.game.externalId,
                    type: PendingOddType.TotalUnder,
                }),
            );
        },

        selectedTotalOver(): boolean {
            return this.pendingOddsDictionary.has(
                createPendingOddKey({
                    externalId: this.game.externalId,
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
                externalId: this.game.externalId,
                type,
            };
            this.$emit("toggleOdd", payload);
        },
    },
});
</script>
