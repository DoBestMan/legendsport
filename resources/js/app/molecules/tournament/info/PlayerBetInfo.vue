<template>
    <section class="layout__content__container layout__content__container__game">
        <div class="tab tab--active" v-if="player">
            <i class="icon icon--person icon--micro m--r--2 b--yellow-1"></i>
            {{ player.name }}
            <div class="delete" style="margin-left: 5px; margin-top: -15px;" @click="close"></div>
        </div>

        <div class="layout__content__container__content layout__content__container__game__content">
            <div class="odds">
                <div class="tab--large d--only--desktop">
                    <div
                        v-for="betTab in betTabs"
                        :key="betTab"
                        class="tab--large__item"
                        :class="{ 'tab--large__item--active': isBetTabSelected(betTab) }"
                        @click="selectBetTab(betTab)"
                    >
                        {{ betTab }}
                    </div>
                </div>
                <div class="odd__container">
                    <PlayerPendingTab
                        v-if="isBetTabSelected(PlayerBetTypeTab.Pending)"
                        :player="player"
                        :window="window"
                    />
                    <PlayerHistoryTab
                        v-if="isBetTabSelected(PlayerBetTypeTab.History)"
                        :player="player"
                        :window="window"
                    />
                </div>
            </div>
        </div>
    </section>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import PlayerPendingTab from "./PlayerPendingTab.vue";
import PlayerHistoryTab from "./PlayerHistoryTab.vue";
import { TournamentPlayer } from "../../../../general/types/user";
import { PlayerBetTypeTab } from "../../../store/modules/playerBetInfo";

export default Vue.extend({
    name: "PlayerBetInfo",
    components: { PlayerPendingTab, PlayerHistoryTab },

    props: {
        window: Object as PropType<Window>,
    },

    data() {
        return {
            isPendingSelected: false,
            isHistorySelected: false,
            playerID: 0,
        };
    },

    created() {
        window.addEventListener("resize", this.isMobile);
    },

    destroyed() {
        window.removeEventListener("resize", this.isMobile);
    },

    computed: {
        player(): TournamentPlayer {
            return this.$stock.getters["playerBetInfo/getPlayer"];
        },
        betTabs(): PlayerBetTypeTab[] {
            return Object.values(PlayerBetTypeTab);
        },
        PlayerBetTypeTab(): typeof PlayerBetTypeTab {
            return PlayerBetTypeTab;
        },
    },

    methods: {
        isMobile(): void {
            if (window.innerWidth > 992) {
            }
        },

        close(): void {
            this.$stock.commit("playerBetInfo/resetPlayerBetSelection");
        },

        isBetTabSelected(type: PlayerBetTypeTab): boolean {
            const tabType = this.$stock.getters["playerBetInfo/getSelectedPlayerBetTypeTab"];
            return tabType === type;
        },

        selectBetTab(type: PlayerBetTypeTab): void {
            this.$stock.commit("playerBetInfo/updateSelectedPlayerBetTypeTab", {
                selectedPlayerBetTypeTab: type,
            });
        },
    },
});
</script>
