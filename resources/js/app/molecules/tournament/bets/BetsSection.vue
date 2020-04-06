<template>
    <section class="col-3 h-100">
        <div class="section bets">
            <div class="title-bar-frm">
                <span class="title">
                    {{ balance | formatChip }}
                </span>
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
import { User } from "../../../../general/types/user";
import { Tournament } from "../../../types/tournament";
import { BetTypeTab, Window } from "../../../types/window";
import HistoryTab from "./HistoryTab.vue";
import ParlayTab from "./ParlayTab.vue";
import PendingTab from "./PendingTab.vue";
import StraightTab from "./StraightTab.vue";
import { UpdateWindowPayload } from "../../../store/modules/window";

export default Vue.extend({
    name: "BetsSection",
    components: {
        HistoryTab,
        ParlayTab,
        PendingTab,
        StraightTab,
    },

    props: {
        window: Object as PropType<Window>,
    },

    computed: {
        tournament(): Tournament {
            return this.window.tournament;
        },

        user(): User | null {
            return this.$stock.state.user.user;
        },

        balance(): number {
            const tournamentPlayer = this.user?.players.find(
                player => player.tournamentId === this.tournament.id,
            );
            return tournamentPlayer?.chips ?? this.tournament.chips;
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
