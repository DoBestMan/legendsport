<template>
    <div class="odd__container__content">
        <div class="odd__container__content__details">
            <div class="odd__container__content__details__line">
                <div class="odd__container__content__details__line__name">
                    {{ game.teamAway }}
                    <div class="odd__container__content__details__line__sport">
                        {{ getSportName(game) }}
                    </div>
                    <span
                        class="odd__container__content__details__line__pitcher"
                        v-if="game.pitcherHome"
                        >{{ game.pitcherAway }}</span
                    >
                </div>

                <div class="odd__container__content__details__line__tags">
                    <div
                        class="odd__container__content__details__line__tags__tag"
                        v-if="Number(moneyLineAway)"
                        :class="{
                            'odd__container__content__details__line__tags__tag--active': selectedMoneyLineAway,
                        }"
                        @click="emitToggleOdd(PendingOddType.MoneyLineAway)"
                    >
                        {{ moneyLineAway | signedNumber }}
                    </div>
                    <div
                        v-else
                        class="odd__container__content__details__line__tags__tag odd__container__content__details__line__tags__tag--empty"
                    ></div>

                    <div
                        class="odd__container__content__details__line__tags__tag"
                        v-if="Number(pointSpreadAwayLine)"
                        :class="{
                            'odd__container__content__details__line__tags__tag--active': selectedSpreadAway,
                        }"
                        @click="emitToggleOdd(PendingOddType.SpreadAway)"
                    >
                        {{ pointSpreadAwayLine | signedNumber }}
                        <div class="odd__container__content__details__line__tags__tag__subtitle">
                            {{ pointSpreadAway | signedNumber }}
                        </div>
                    </div>
                    <div
                        v-else
                        class="odd__container__content__details__line__tags__tag odd__container__content__details__line__tags__tag--empty"
                    ></div>

                    <div
                        class="odd__container__content__details__line__tags__tag"
                        v-if="Number(totalNumber)"
                        :class="{
                            'odd__container__content__details__line__tags__tag--active': selectedTotalUnder,
                        }"
                        @click="emitToggleOdd(PendingOddType.TotalUnder)"
                    >
                        U {{ totalNumber }}
                        <div class="odd__container__content__details__line__tags__tag__subtitle">
                            {{ underLine | signedNumber }}
                        </div>
                    </div>
                    <div
                        v-else
                        class="odd__container__content__details__line__tags__tag odd__container__content__details__line__tags__tag--empty"
                    ></div>
                </div>
            </div>
            <div class="odd__container__content__details__line">
                <div class="odd__container__content__details__line__name">
                    {{ game.teamHome }}
                    <span
                        class="odd__container__content__details__line__pitcher"
                        v-if="game.pitcherHome"
                        >{{ game.pitcherHome }}</span
                    >
                </div>

                <div class="odd__container__content__details__line__tags">
                    <div
                        class="odd__container__content__details__line__tags__tag"
                        v-if="Number(moneyLineHome)"
                        :class="{
                            'odd__container__content__details__line__tags__tag--active': selectedMoneyLineHome,
                        }"
                        @click="emitToggleOdd(PendingOddType.MoneyLineHome)"
                    >
                        {{ moneyLineHome | signedNumber }}
                    </div>
                    <div
                        v-else
                        class="odd__container__content__details__line__tags__tag odd__container__content__details__line__tags__tag--empty"
                    ></div>

                    <div
                        class="odd__container__content__details__line__tags__tag"
                        v-if="Number(pointSpreadHomeLine)"
                        :class="{
                            'odd__container__content__details__line__tags__tag--active': selectedSpreadHome,
                        }"
                        @click="emitToggleOdd(PendingOddType.SpreadHome)"
                    >
                        {{ pointSpreadHomeLine | signedNumber }}
                        <div class="odd__container__content__details__line__tags__tag__subtitle">
                            {{ pointSpreadHome | signedNumber }}
                        </div>
                    </div>
                    <div
                        v-else
                        class="odd__container__content__details__line__tags__tag odd__container__content__details__line__tags__tag--empty"
                    ></div>

                    <div
                        class="odd__container__content__details__line__tags__tag"
                        v-if="Number(totalNumber)"
                        :class="{
                            'odd__container__content__details__line__tags__tag--active': selectedTotalOver,
                        }"
                        @click="emitToggleOdd(PendingOddType.TotalOver)"
                    >
                        O {{ totalNumber }}
                        <div class="odd__container__content__details__line__tags__tag__subtitle">
                            {{ overLine | signedNumber }}
                        </div>
                    </div>
                    <div
                        v-else
                        class="odd__container__content__details__line__tags__tag odd__container__content__details__line__tags__tag--empty"
                    ></div>
                </div>
            </div>
        </div>

        <!-- ToDo: how to get +23 -->
        <div class="odd__container__content__odd">
            <div class="odd__container__content__odd__details">
                +0
            </div>
        </div>
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

        getSportName(game: Game): string {
            const dict: ReadonlyMap<string, string> = this.$stock.getters["sport/sportDictionary"];
            return dict.get(game.sportId) ?? String(game.sportId);
        },
    },
});
</script>
