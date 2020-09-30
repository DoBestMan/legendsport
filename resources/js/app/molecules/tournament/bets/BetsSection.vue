<template>
    <section class="layout__content__sidebar">
        <div class="layout__content__sidebar__header">
            <div class="layout__content__sidebar__header__bet" v-if="isRegistered">
                <i class="icon m--r--2 icon--slip"></i>
                <div class="layout__content__sidebar__header__bet__content">
                    <div class="layout__content__sidebar__header__bet__content__title">
                        Bet Slip
                    </div>
                    <div class="layout__content__sidebar__header__bet__content__group">
                        <div class="layout__content__sidebar__header__bet__content__group__coins">
                            <i
                                class="icon icon--atom icon--color--yellow-2 icon--coins m--r--1"
                            ></i>
                            Balance
                        </div>
                        <div class="layout__content__sidebar__header__bet__content__group__balance">
                            {{ player.chips | formatChip }}
                        </div>
                    </div>
                </div>
            </div>
            <RegisterNowButton class="button--large" v-else :tournament="tournament" />

            <div class="tab--large">
                <div
                    v-for="betTab in betTabs"
                    :key="betTab"
                    class="tab--large__item"
                    :class="{
                        'tab--large__item--active': isBetTabSelected(betTab),
                    }"
                    @click="selectBetTab(betTab)"
                >
                    {{ betTab }}
                </div>
            </div>

            <div
                class="layout__content__sidebar__header__input"
                v-if="isBetTabSelected(BetTypeTab.Straight)"
            >
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
        </div>

        <StraightTab v-if="isBetTabSelected(BetTypeTab.Straight)" :window="window" />
        <ParlayTab v-if="isBetTabSelected(BetTypeTab.Parlay)" :window="window" />
        <PendingTab v-if="isBetTabSelected(BetTypeTab.Pending)" :window="window" />
        <HistoryTab v-if="isBetTabSelected(BetTypeTab.History)" :window="window" />

        <PlaceBet v-if="isBetTabSelected(BetTypeTab.Straight)" :window="window" />
        <ParlayPlaceBet v-if="isBetTabSelected(BetTypeTab.Parlay)" :window="window" />
    </section>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { UserPlayer } from "../../../../general/types/user";
import { Tournament } from "../../../types/tournament";
import { BetTypeTab, Window } from "../../../types/window";
import HistoryTab from "./HistoryTab.vue";
import ParlayTab from "./ParlayTab.vue";
import PendingTab from "./PendingTab.vue";
import StraightTab from "./StraightTab.vue";
import PlaceBet from "./PlaceBet.vue";
import ParlayPlaceBet from "./ParlayPlaceBet.vue";
import ChipInput from "../../../../general/components/ChipInput.vue";
import RegisterNowButton from "../../../components/RegisterNowButton.vue";
import { UpdateOddsWagerPayload, UpdateWindowPayload } from "../../../store/modules/window";

export default Vue.extend({
    name: "BetsSection",

    components: {
        HistoryTab,
        ParlayTab,
        PendingTab,
        RegisterNowButton,
        StraightTab,
        PlaceBet,
        ChipInput,
        ParlayPlaceBet,
    },

    props: {
        window: Object as PropType<Window>,
    },

    data() {
        return {
            wager: 0,
        };
    },

    computed: {
        tournament(): Tournament {
            return this.window.tournament;
        },

        player(): UserPlayer | null {
            const playersDict: ReadonlyMap<number, UserPlayer> = this.$stock.getters[
                "user/playersDictByTournament"
            ];
            return playersDict.get(this.tournament.id) ?? null;
        },

        isRegistered(): boolean {
            return !!this.player;
        },

        betTabs(): BetTypeTab[] {
            return Object.values(BetTypeTab);
        },

        BetTypeTab(): typeof BetTypeTab {
            return BetTypeTab;
        },
    },

    methods: {
        isBetTabSelected(type: BetTypeTab): boolean {
            return this.window.selectedBetTypeTab === type;
        },

        selectBetTab(type: BetTypeTab): void {
            const payload: UpdateWindowPayload = {
                id: this.window.id,
                selectedBetTypeTab: type,
            };
            this.$stock.commit("window/updateWindow", payload);
        },

        updateOddsWager() {
            const payload: UpdateOddsWagerPayload = {
                windowId: this.window.id,
                wager: this.wager,
            };
            this.$stock.commit("window/updateOddsWager", payload);
        },
    },
});
</script>
