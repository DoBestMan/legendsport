<template>
    <div>
        <div class="switch">
            <div
                v-for="tournamentInfo in tournamentInfoTabs"
                :key="tournamentInfo"
                class="switch__item"
                :class="{
                    'switch__item--active': isTournamentInfoSelected(tournamentInfo),
                }"
                @click="selectTournamentInfoTab(tournamentInfo)"
            >
                <i
                    v-if="tournamentInfo === TournamentInfoTab.Games"
                    class="icon icon--games m--r--1"
                ></i>
                <i
                    v-if="tournamentInfo === TournamentInfoTab.Ranks"
                    class="icon icon--micro icon--rank m--r--1"
                ></i>
                <i
                    v-if="tournamentInfo === TournamentInfoTab.Prizes"
                    class="icon icon--micro icon--cup m--r--1"
                ></i>
                {{ tournamentInfo }}
            </div>
        </div>

        <TournamentGamesTable
            v-if="isTournamentInfoSelected(TournamentInfoTab.Games)"
            :games="games"
        />
        <TournamentRankTable
            v-if="isTournamentInfoSelected(TournamentInfoTab.Ranks)"
            :players="players"
        />
        <PrizePool
            v-if="isTournamentInfoSelected(TournamentInfoTab.Prizes)"
            :tournament="tournament"
        />
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import TournamentGamesTable from "../../home/TournamentGamesTable.vue";
import TournamentRankTable from "../../general/TournamentRankTable.vue";
import PrizePool from "./PrizePool.vue";
import { TournamentInfoTab, Window } from "../../../types/window";
import { Tournament } from "../../../types/tournament";
import { UpdateWindowPayload } from "resources/js/app/store/modules/window";
import { Game } from "../../../types/game";
import { Player } from "../../../types/player";

export default Vue.extend({
    name: "InfoDetailSection",

    components: {
        TournamentGamesTable,
        TournamentRankTable,
        PrizePool,
    },

    props: {
        tournament: Object as PropType<Tournament | null>,
        window: Object as PropType<Window>,
    },

    computed: {
        tournamentInfoTabs(): TournamentInfoTab[] {
            return Object.values(TournamentInfoTab);
        },

        TournamentInfoTab(): typeof TournamentInfoTab {
            return TournamentInfoTab;
        },

        games(): Game[] {
            return this.tournament?.games ?? [];
        },

        players(): Player[] {
            return this.tournament?.players ?? [];
        },
    },

    methods: {
        isTournamentInfoSelected(type: TournamentInfoTab): boolean {
            return this.window.selectedTournamentInfoTab === type;
        },

        selectTournamentInfoTab(type: TournamentInfoTab): void {
            const payload: UpdateWindowPayload = {
                id: this.window.id,
                selectedTournamentInfoTab: type,
            };
            this.$stock.commit("window/updateWindow", payload);
        },
    },
});
</script>
