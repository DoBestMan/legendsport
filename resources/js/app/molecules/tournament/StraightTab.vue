<template>
    <div class="tab-content-frm">
        <div class="tab-header-frm">
            <div class="field">
                <strong class="field-title">Bet</strong>
                <MoneyInput v-model="bet" />
            </div>

            <button class="button button-action item-gold mx-3 px-5" @click="updateOddsBet">
                Set to all bets
            </button>

            <div class="button-trash" @click="removeOdds">
                <i class="icon fas fa-trash-alt"></i>
            </div>
        </div>

        <div class="items-frm">
            <StraightItem
                :key="`${pendingOdd.eventId}#${pendingOdd.type}`"
                :pendingOdd="pendingOdd"
                :game="getGame(pendingOdd.eventId)"
                :value="pendingOdd.bet"
                @delete="removeOdd(pendingOdd)"
                @change="updateOdd(pendingOdd, $event)"
                v-for="pendingOdd in pendingOdds"
            />
            <div v-if="!pendingOdds.length" class="h3 text-center p-5">
                No records
            </div>
        </div>

        <div v-if="pendingOdds.length" class="tab-footer-frm">
            <div class="header-frm">
                <div class="h4">SUMMARY</div>

                <div class="button-trash" @click="removeOdds">
                    <i class="icon fas fa-trash-alt"></i>
                </div>
            </div>

            <div class="content-frm row">
                <div class="col">
                    <div>Total<br />Active&nbsp;Bets</div>
                    <div class="h4">{{ pendingOdds.length }}</div>
                </div>
                <div class="col">
                    <div>Total<br />Bets</div>
                    <div><Money class="h4" :value="totalBets" /></div>
                </div>
                <div class="col">
                    <div>Total<br />Potential&nbsp;Bets</div>
                    <div><Money class="h4" :value="totalWin" /></div>
                </div>
            </div>

            <div class="footer-frm">
                <button class="button-place-bet button-action item-gold" @click="placeBet">
                    Place Bet
                </button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { PendingOdd, Window } from "../../types/window";
import { DeepReadonly, DeepReadonlyArray } from "../../../general/types/types";
import StraightItem from "./StraightItem.vue";
import { Game } from "../../types/game";
import { PendingOddPayload, UpdateOddsBetPayload } from "../../store/modules/window";
import MoneyInput from "../../components/MoneyInput.vue";
import { calculateWinFromAmericanOdd, getPendingOddValue } from "../../utils/game/bet";
import { Odd } from "../../../general/types/odd";
import Money from "../../components/Money.vue";

export default Vue.extend({
    name: "StraightTab",
    components: { Money, MoneyInput, StraightItem },
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

        totalBets(): number {
            return this.pendingOdds
                .map(pendingOdd => pendingOdd.bet ?? 0)
                .reduce((a, b) => a + b, 0);
        },

        totalWin(): number {
            return this.pendingOdds
                .map(pendingOdd => {
                    const dictionary: ReadonlyMap<string, Odd> = this.$store.getters[
                        "odd/oddDictionary"
                    ];
                    const odd = dictionary.get(pendingOdd.eventId);
                    const oddValue = odd ? getPendingOddValue(pendingOdd, odd) : 0;
                    return calculateWinFromAmericanOdd(oddValue, pendingOdd.bet ?? 0);
                })
                .reduce((a, b) => a + b, 0);
        },
    },

    methods: {
        getGame(eventId: string): Game | null {
            return this.window.tournament.games.find(game => game.event_id === eventId) ?? null;
        },

        updateOdd(pendingOdd: DeepReadonly<PendingOdd>, value: number) {
            const payload: PendingOddPayload = {
                windowId: this.window.id,
                eventId: pendingOdd.eventId,
                type: pendingOdd.type,
                bet: value,
            };
            this.$store.commit("window/updateOdd", payload);
        },

        removeOdd(pendingOdd: DeepReadonly<PendingOdd>) {
            const payload: PendingOddPayload = {
                windowId: this.window.id,
                ...pendingOdd,
            };
            this.$store.commit("window/toggleOdd", payload);
        },

        updateOddsBet() {
            const payload: UpdateOddsBetPayload = {
                windowId: this.window.id,
                bet: this.bet,
            };
            this.$store.commit("window/updateOddsBet", payload);
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
