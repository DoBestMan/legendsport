<template>
    <div class="layout__content__sidebar__bet" v-if="hasEnoughPendingOdds">
        <div class="layout__content__sidebar__bet__row">
            <div style="margin-right: 10px;">
                <strong class="layout__content__sidebar__bet__row__title">Bet</strong>
                <ChipInput :value="wager" @input="updateWager" />
            </div>
            <div>
                <strong class="layout__content__sidebar__bet__row__title">Win</strong>
                <ChipInput :value="win" readonly />
            </div>
        </div>
        <div class="d--flex">
            <div class="button button--square" @click="removeOdds">
                <i class="icon icon--delete icon--color--light-2"></i>
            </div>
            <PlaceBetButton
                :tournament="window.tournament"
                :disabled="!canPlaceBet"
                @placeBet="placeBet"
            />
        </div>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import ChipInput from "../../../../general/components/ChipInput.vue";
import { americanToDecimalOdd, getPendingOddValue } from "../../../utils/game/bet";
import PlaceBetButton from "./PlaceBetButton.vue";
import { PlaceParlayBetPayload } from "../../../store/modules/placeBet";
import { BetTypeTab, PendingOdd, Window } from "../../../types/window";
import { UpdateWindowPayload } from "../../../store/modules/window";
import { Odd } from "../../../types/odd";
import { UserPlayer } from "../../../../general/types/user";
import { Tournament } from "../../../types/tournament";

export default Vue.extend({
    name: "ParlayPlaceBet",

    components: { ChipInput, PlaceBetButton },

    props: {
        window: Object as PropType<Window>,
    },

    computed: {
        tournament(): Tournament {
            return this.window.tournament;
        },

        wager(): number {
            return this.window.parlayWager;
        },

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
            const playersDict: ReadonlyMap<number, UserPlayer> = this.$stock.getters[
                "user/playersDictByTournament"
            ];
            const tournamentPlayer = playersDict.get(this.tournament.id);
            return tournamentPlayer?.chips ?? this.tournament.chips;
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
                    const odd = dictionary.get(pendingOdd.externalId);
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
                tournamentId: this.tournament.id,
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
            }
        },
    },
});
</script>
