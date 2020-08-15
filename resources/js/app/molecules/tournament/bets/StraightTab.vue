<template>
    <div>
        <div class="layout__content__sidebar__header__input">
            <div class="form">
                <div class="form__control">
                    <div class="form__control__icon form__control__icon--left">
                        <i class="icon icon--micro icon--usd icon--color--light-1"></i>
                    </div>
                    <ChipInput v-model="wager" placeholder="Bet" />
                </div>
            </div>
            <div class="button button--small button--yellow m--l--4" @click="updateOddsWager">
                SET TO ALL
            </div>
        </div>

        <div class="bets__container__scroll">
            <StraightItem
                :key="`${pendingOdd.externalId}#${pendingOdd.type}`"
                :pendingOdd="pendingOdd"
                :game="gameDict.get(pendingOdd.externalId)"
                :value="pendingOdd.wager"
                @delete="removeOdd(pendingOdd)"
                @change="updateOdd(pendingOdd, $event)"
                v-for="pendingOdd in pendingOdds"
            />
            <div v-if="!pendingOdds.length" class="h3 text-center p-5">No records</div>
        </div>

        <div class="layout__content__sidebar__bet" v-if="pendingOdds.length">
            <div class="bet__footer__line__total bet__footer__line__padding">
                <div class="bet__footer__line__name">
                    Total Active Bets
                </div>
                <div class="bet__footer__line__detail">
                    {{ pendingOdds.length }}
                </div>
            </div>
            <div class="bet__footer__line__total bet__footer__line__padding">
                <div class="bet__footer__line__name">
                    Total Bet
                </div>
                <div class="bet__footer__line__detail">
                    {{ totalBets | formatChip }}
                </div>
            </div>
            <div class="bet__footer__line__total bet__footer__line__padding">
                <div class="bet__footer__line__name">
                    Total Potential Win
                </div>
                <div class="bet__footer__line__detail">
                    {{ totalWin | formatChip }}
                </div>
            </div>
            <div class="bet__footer__line__total bet__footer__line__padding">
                <div class="bet__footer__line__name">
                    <div class="bet__footer__line__delete" @click="removeOdds">
                        <i class="icon icon--delete icon--large"></i>
                    </div>
                </div>
                <PlaceBetButton
                    :tournament="window.tournament"
                    :disabled="!canPlaceBet"
                    @placeBet="placeBet"
                />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { BetTypeTab, PendingOdd, Window } from "../../../types/window";
import { DeepReadonly } from "../../../../general/types/types";
import StraightItem from "./StraightItem.vue";
import { Game } from "../../../types/game";
import {
    PendingOddPayload,
    UpdateOddsWagerPayload,
    UpdateWindowPayload,
} from "../../../store/modules/window";
import ChipInput from "../../../../general/components/ChipInput.vue";
import { calculateWinFromAmericanOdd, getPendingOddValue } from "../../../utils/game/bet";
import { PlaceStraightBetPayload } from "../../../store/modules/placeBet";
import PlaceBetButton from "./PlaceBetButton.vue";
import { UserPlayer } from "../../../../general/types/user";
import { Odd } from "../../../types/odd";

export default Vue.extend({
    name: "StraightTab",
    components: { ChipInput, PlaceBetButton, StraightItem },

    props: {
        window: Object as PropType<Window>,
    },

    data() {
        return {
            wager: 0,
        };
    },

    computed: {
        canPlaceBet(): boolean {
            return (
                this.pendingOdds.length > 0 &&
                this.pendingOdds.every(pendingOdd => pendingOdd.wager ?? 0 > 0) &&
                this.totalBets <= this.balance
            );
        },

        gameDict(): ReadonlyMap<string, Game> {
            return new Map(this.window.tournament.games.map(game => [game.externalId, game]));
        },

        pendingOdds(): PendingOdd[] {
            return this.window.pendingOdds.filter(pendingOdd =>
                this.gameDict.has(pendingOdd.externalId),
            );
        },

        balance(): number {
            const playersDict: ReadonlyMap<number, UserPlayer> = this.$stock.getters[
                "user/playersDictByTournament"
            ];
            const tournamentPlayer = playersDict.get(this.window.tournament.id);
            return tournamentPlayer?.chips ?? this.window.tournament.chips;
        },

        totalBets(): number {
            return this.pendingOdds
                .map(pendingOdd => pendingOdd.wager ?? 0)
                .reduce((a, b) => a + b, 0);
        },

        totalWin(): number {
            return this.pendingOdds
                .map(pendingOdd => {
                    const dictionary: ReadonlyMap<string, Odd> = this.$stock.getters[
                        "odd/oddDictionary"
                    ];
                    const odd = dictionary.get(pendingOdd.externalId);
                    const oddValue = odd ? getPendingOddValue(pendingOdd, odd) : 0;
                    return calculateWinFromAmericanOdd(oddValue, pendingOdd.wager ?? 0);
                })
                .reduce((a, b) => a + b, 0);
        },
    },

    methods: {
        updateOdd(pendingOdd: DeepReadonly<PendingOdd>, value: number) {
            const payload: PendingOddPayload = {
                windowId: this.window.id,
                tournamentEventId: pendingOdd.tournamentEventId,
                externalId: pendingOdd.externalId,
                type: pendingOdd.type,
                wager: value,
            };
            this.$stock.commit("window/updateOdd", payload);
        },

        removeOdd(pendingOdd: DeepReadonly<PendingOdd>) {
            const payload: PendingOddPayload = {
                windowId: this.window.id,
                ...pendingOdd,
            };
            this.$stock.dispatch("window/toggleOdd", payload);
        },

        updateOddsWager() {
            const payload: UpdateOddsWagerPayload = {
                windowId: this.window.id,
                wager: this.wager,
            };
            this.$stock.commit("window/updateOddsWager", payload);
        },

        removeOdds() {
            this.$stock.commit("window/removeOdds", this.window.id);
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

            const payload: PlaceStraightBetPayload = {
                tournamentId: this.window.tournament.id,
                pending_odds: this.pendingOdds.map(pendingOdd => ({
                    type: pendingOdd.type,
                    event_id: pendingOdd.tournamentEventId,
                    wager: pendingOdd.wager!,
                })),
            };
            await this.$stock.dispatch("placeBet/placeStraight", payload);

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
