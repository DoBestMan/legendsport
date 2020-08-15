<template>
    <section class="layout__content__sidebar">
        <div class="layout__content__sidebar__header">
            <div class="layout__content__sidebar__header__bet">
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
                            <!-- TODO: -->
                            1,000,000
                        </div>
                    </div>
                </div>
                <!-- TODO: -->
                <!-- <RegisterNowButton v-else :tournament="tournament" /> -->
            </div>

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
        </div>

        <StraightTab v-if="isBetTabSelected(BetTypeTab.Straight)" :window="window" />
        <ParlayTab v-if="isBetTabSelected(BetTypeTab.Parlay)" :window="window" />
        <PendingTab v-if="isBetTabSelected(BetTypeTab.Pending)" :window="window" />
        <HistoryTab v-if="isBetTabSelected(BetTypeTab.History)" :window="window" />
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
import RegisterNowButton from "../../../components/RegisterNowButton.vue";
import { UpdateWindowPayload } from "../../../store/modules/window";

export default Vue.extend({
    name: "BetsSection",
    components: {
        HistoryTab,
        ParlayTab,
        PendingTab,
        RegisterNowButton,
        StraightTab,
    },

    props: {
        window: Object as PropType<Window>,
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
    },
});
</script>
