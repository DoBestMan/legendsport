<template>
    <section class="col-3 h-100">
        <div class="section bets">
            <div class="title-bar-frm">
                <span v-if="isRegistered" class="title">
                    <i class="fas fa-coins"></i>
                    Balance: {{ player.chips | formatChip }} ({{
                        player.pendingChips | formatChip
                    }})
                </span>
                <RegisterNowButton v-else :tournament="tournament" />
            </div>

            <div class="tabs-frm">
                <div class="tab-frm" v-for="betTab in betTabs">
                    <button
                        type="button"
                        class="btn tab"
                        :class="{ active: isBetTabSelected(betTab) }"
                        @click="selectBetTab(betTab)"
                    >
                        {{ betTab }}
                    </button>
                    <span class="separator">|</span>
                </div>
            </div>

            <PendingTab v-if="isBetTabSelected(BetTypeTab.Pending)" :window="window" />
            <HistoryTab v-if="isBetTabSelected(BetTypeTab.History)" :window="window" />
            <StraightTab v-if="isBetTabSelected(BetTypeTab.Straight)" :window="window" />
            <ParlayTab v-if="isBetTabSelected(BetTypeTab.Parlay)" :window="window" />
        </div>
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
import { UpdateWindowPayload } from "../../../store/modules/window";
import RegisterNowButton from "../../../components/RegisterNowButton.vue";

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
