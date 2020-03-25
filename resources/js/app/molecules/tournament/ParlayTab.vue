<template>
    <div class="tab-content-frm">
        <div class="items-frm">
            <transition-group name="fade" tag="div">
                <ParlayItem
                    :key="`${pendingOdd.eventId}#${pendingOdd.type}`"
                    :pendingOdd="pendingOdd"
                    :game="getGame(pendingOdd.eventId)"
                    :value="pendingOdd.bet"
                    @delete="removeOdd(pendingOdd)"
                    v-for="pendingOdd in pendingOdds"
                />
            </transition-group>

            <div v-if="!pendingOdds.length" class="h3 text-center p-5">
                No records
            </div>
        </div>

        <transition name="slidey">
            <div v-if="pendingOdds.length" class="tab-footer-frm">
                <div class="header-frm">
                    <div class="h4">SUMMARY</div>

                    <div class="button-trash" @click="removeOdds">
                        <i class="icon fas fa-trash-alt"></i>
                    </div>
                </div>

                <div class="content-frm">
                    <div class="bet-frm">
                        <div class="field">
                            <strong class="field-title">Bet</strong>
                            <MoneyInput v-model="bet" />
                        </div>
                        <div class="field">
                            <strong class="field-title">Win</strong>
                            <MoneyInput class="input-win" :value="win" readonly />
                        </div>
                    </div>
                </div>

                <div class="footer-frm">
                    <button class="button-place-bet button-action item-gold" @click="placeBet">
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

export default Vue.extend({
    name: "ParlayTab",
    components: { MoneyInput, ParlayItem },

    props: {
        window: Object as PropType<DeepReadonly<Window>>,
    },

    data() {
        return {
            bet: 0,
        };
    },

    computed: {
        pendingOdds(): DeepReadonlyArray<PendingOdd> {
            return this.window.pendingOdds;
        },

        win(): number {
            const multiplier = this.window.pendingOdds
                .map(pendingOdd => {
                    const dictionary: ReadonlyMap<string, Odd> = this.$store.getters[
                        "odd/oddDictionary"
                    ];
                    const odd = dictionary.get(pendingOdd.eventId);
                    const oddValue = odd ? getPendingOddValue(pendingOdd, odd) : 0;
                    return 1 + americanToDecimalOdd(oddValue);
                })
                .reduce((a, b) => a * b, 1);

            return this.bet * multiplier - this.bet;
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
            this.$store.commit("window/toggleOdd", payload);
        },

        removeOdds() {
            this.$store.commit("window/removeOdds", this.window.id);
        },

        placeBet() {
            // TODO Implement it
            alert("Not implemented yet");
        },
    },
});
</script>
