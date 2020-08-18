<template>
    <div class="layout__content__sidebar__bet" v-if="pendingOdds.length">
        <div class="layout__content__sidebar__bet__row">
            <div class="layout__content__sidebar__bet__row__title">
                Total Active Bets
            </div>
            <div class="layout__content__sidebar__bet__row__value">
                {{ pendingOdds.length }}
            </div>
        </div>
        <div class="layout__content__sidebar__bet__row">
            <div class="layout__content__sidebar__bet__row__title">
                Total Bet
            </div>
            <div class="layout__content__sidebar__bet__row__value">
                {{ totalBets | formatChip }}
            </div>
        </div>
        <div class="layout__content__sidebar__bet__row m--b--6">
            <div class="layout__content__sidebar__bet__row__title">
                Total Potential Win
            </div>
            <div class="layout__content__sidebar__bet__row__value">
                {{ totalWin | formatChip }}
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
import { BetTypeTab, PendingOdd, Window } from "../../../types/window";
import { Game } from "../../../types/game";
import { UserPlayer } from "../../../../general/types/user";
import { UpdateWindowPayload } from "../../../store/modules/window";
import { PlaceStraightBetPayload } from "../../../store/modules/placeBet";
import { Odd } from "../../../types/odd";
import { calculateWinFromAmericanOdd, getPendingOddValue } from "../../../utils/game/bet";
import PlaceBetButton from "./PlaceBetButton.vue";

export default Vue.extend({
    name: "PlaceBet",

    components: { PlaceBetButton },

    props: {
        window: Object as PropType<Window>,
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
