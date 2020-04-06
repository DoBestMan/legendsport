<template>
    <div class="tab-content-frm">
        <div class="items-frm">
            <ParlayItem
                :key="`${pendingOdd.eventId}#${pendingOdd.type}`"
                :pendingOdd="pendingOdd"
                :game="getGame(pendingOdd.eventId)"
                :value="pendingOdd.wager"
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
                            <ChipInput :min="100" :value="wager" @input="updateWager" />
                        </div>
                        <div class="field">
                            <strong class="field-title">Win</strong>
                            <ChipInput class="input-win" :value="win" readonly />
                        </div>
                    </div>
                </div>

                <PlaceBetButton
                    :tournamentId="window.tournament.id"
                    :disabled="!canPlaceBet"
                    @placeBet="placeBet"
                />
            </div>
        </transition>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import ParlayItem from "./ParlayItem.vue";
import { DeepReadonly } from "../../../../general/types/types";
import { BetTypeTab, PendingOdd, Window } from "../../../types/window";
import { PendingOddPayload, UpdateWindowPayload } from "../../../store/modules/window";
import { Game } from "../../../types/game";
import MoneyInput from "../../../components/MoneyInput.vue";
import { americanToDecimalOdd, getPendingOddValue } from "../../../utils/game/bet";
import { Odd } from "../../../../general/types/odd";
import { PlaceParlayBetPayload } from "../../../store/modules/placeBet";
import PlaceBetButton from "./PlaceBetButton.vue";
import ChipInput from "../../../components/ChipInput.vue";

export default Vue.extend({
    name: "ParlayTab",
    components: { ChipInput, MoneyInput, ParlayItem, PlaceBetButton },

    props: {
        window: Object as PropType<Window>,
    },

    computed: {
        isAuthenticated(): boolean {
            return !!this.$stock.state.user.user;
        },

        wager(): number {
            return this.window.parlayWager;
        },

        // TODO Display limit error

        canPlaceBet(): boolean {
            return (
                this.wager > 0 &&
                this.hasEnoughPendingOdds &&
                this.multiplier > 1 &&
                this.wager <= this.balance
            );
        },

        pendingOdds(): PendingOdd[] {
            return this.window.pendingOdds;
        },

        balance(): number {
            const tournamentPlayer = this.$stock.state.user.user?.players.find(
                player => player.tournamentId === this.window.tournament.id,
            );
            return tournamentPlayer?.chips ?? this.window.tournament.chips;
        },

        win(): number {
            return Math.floor(this.wager * this.multiplier - this.wager);
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
            this.$stock.dispatch("window/toggleOdd", payload);
        },

        removeOdds() {
            this.$stock.commit("window/removeOdds", this.window.id);
            this.updateWager(0);
        },

        updateWager(value: number) {
            const payload: UpdateWindowPayload = {
                id: this.window.id,
                parlayWager: value,
            };
            this.$stock.commit("window/updateWindow", payload);
        },

        displayPendingTab() {
            const payload: UpdateWindowPayload = {
                id: this.window.id,
                selectedBetTypeTab: BetTypeTab.Pending,
            };
            this.$stock.commit("window/updateWindow", payload);
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
                wager: this.wager,
            };
            await this.$stock.dispatch("placeBet/placeParlay", payload);

            if (this.$stock.state.placeBet.error) {
                const error = this.$stock.state.placeBet.error;
                this.$toast.error(error?.response?.data?.message ?? error.message);
            } else {
                this.removeOdds();
                this.displayPendingTab();
                this.$toast.success("You've placed a parlay bet.");
            }
        },
    },
});
</script>
