<template>
    <div class="tab-content-frm">
        <div class="items-frm">
            <ParlayItem
                :key="`${pendingOdd.eventId}#${pendingOdd.type}`"
                :pendingOdd="pendingOdd"
                :game="getGame(pendingOdd.eventId)"
                :value="pendingOdd.bet"
                @delete="removeOdd(pendingOdd)"
                v-for="pendingOdd in pendingOdds"
            />
            <div v-if="!pendingOdds.length" class="h3 text-center p-5">
                No records
            </div>
        </div>

        <transition name="slidey">
            <div v-if="hasEnoughPendingOdds" class="tab-footer-frm">
                <div class="header-frm">
                    <div class="h4">SUMMARY</div>

                    <div class="btn button-trash" @click="removeOdds">
                        <i class="icon fas fa-trash-alt"></i>
                    </div>
                </div>

                <div class="content-frm">
                    <div class="bet-frm">
                        <div class="field">
                            <strong class="field-title">Bet</strong>
                            <MoneyInput v-model="wager" />
                        </div>
                        <div class="field">
                            <strong class="field-title">Win</strong>
                            <MoneyInput class="input-win" :value="win" readonly />
                        </div>
                    </div>
                </div>

                <div class="footer-frm">
                    <button
                        class="btn button-place-bet button-action"
                        @click="placeBet"
                        :disabled="!canPlaceBet"
                    >
                        Place Bet
                    </button>
                </div>
            </div>
        </transition>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import ParlayItem from "./ParlayItem.vue";
import { DeepReadonly, DeepReadonlyArray } from "../../../general/types/types";
import { PendingOdd, Window } from "../../types/window";
import { PendingOddPayload } from "../../store/modules/window";
import { Game } from "../../types/game";
import MoneyInput from "../../components/MoneyInput.vue";
import { americanToDecimalOdd, getPendingOddValue } from "../../utils/game/bet";
import { Odd } from "../../../general/types/odd";
import { PlaceParlayBetPayload } from "../../store/modules/placeBet";

export default Vue.extend({
    name: "ParlayTab",
    components: { MoneyInput, ParlayItem },

    props: {
        window: Object as PropType<DeepReadonly<Window>>,
    },

    data() {
        return {
            wager: 0,
        };
    },

    computed: {
        pendingOdds(): DeepReadonlyArray<PendingOdd> {
            return this.window.pendingOdds;
        },

        win(): number {
            return this.wager * this.multiplier - this.wager;
        },

        multiplier(): number {
            return this.window.pendingOdds
                .map(pendingOdd => {
                    const dictionary: ReadonlyMap<string, Odd> = this.$stock.getters[
                        "odd/oddDictionary"
                    ];
                    const odd = dictionary.get(pendingOdd.eventId);
                    const oddValue = odd ? getPendingOddValue(pendingOdd, odd) : 0;
                    return 1 + americanToDecimalOdd(oddValue);
                })
                .reduce((a, b) => a * b, 1);
        },

        canPlaceBet(): boolean {
            return this.wager > 0 && this.hasEnoughPendingOdds && this.multiplier > 1;
        },

        hasEnoughPendingOdds(): boolean {
            return this.pendingOdds.length > 1;
        },
    },

    methods: {
        getGame(eventId: string): Game | null {
            return this.window.tournament.games.find(game => game.event_id === eventId) ?? null;
        },

        removeOdd(pendingOdd: DeepReadonly<PendingOdd>) {
            const payload: PendingOddPayload = {
                windowId: this.window.id,
                ...pendingOdd,
            };
            this.$stock.commit("window/toggleOdd", payload);
        },

        removeOdds() {
            this.$stock.commit("window/removeOdds", this.window.id);
        },

        async placeBet() {
            if (!this.canPlaceBet) {
                return;
            }

            const payload: PlaceParlayBetPayload = {
                tournamentId: this.window.tournament.id,
                pending_odds: this.pendingOdds.map(pendingOdd => ({
                    type: pendingOdd.type,
                    event_id: pendingOdd.tournamentEventId,
                })),
                wager: this.wager * 100,
            };
            await this.$stock.dispatch("placeBet/placeParlay", payload);

            if (this.$stock.state.placeBet.error) {
                const error = this.$stock.state.placeBet.error;
                this.$toast.error(error?.response?.data?.message ?? error.message);
            } else {
                this.removeOdds();
                this.wager = 0;
                this.$toast.success("You've placed a parlay bet.");
            }
        },
    },
});
</script>
