<template>
    <section class="col-6 h-100">
        <div class="section matches">
            <div class="tabs-frm">
                <div class="tab-frm">
                    <button
                        type="button"
                        class="btn tab"
                        :class="{ active: areAllSportsSelected }"
                        @click="selectAllSports"
                    >
                        All
                    </button>
                    <span class="separator">|</span>
                </div>

                <div class="tab-frm" v-for="sportId in tournament.sportIds">
                    <button
                        type="button"
                        class="btn tab"
                        :class="{ active: isSportSelected(sportId) }"
                        @click="toggleSport(sportId)"
                    >
                        {{ getSportName(sportId) }}
                    </button>
                    <span class="separator">|</span>
                </div>
            </div>

            <div class="actions-frm">
                <button type="button" class="btn game-line">Game Line</button>
                <button type="button" class="btn game-first-half checked">
                    1st half
                </button>
                <button type="button" class="btn update">Update</button>
            </div>

            <div class="table-frm overflow-auto">
                <table class="match table" v-for="(games, date) in groupedGames" :key="date">
                    <thead class="thead">
                        <tr class="tr">
                            <th class="th col-datetime" scope="col">{{ date | toDateTime }}</th>
                            <th class="th col-money" scope="col">Money line</th>
                            <th class="th col-spread" scope="col">Spread</th>
                            <th class="th col-total" scope="col">Total</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        <GameRow
                            :key="game.id"
                            :window="window"
                            :game="game"
                            @toggleOdd="toggleOdd"
                            v-for="game in games"
                        />
                    </tbody>
                </table>
                <div v-if="!Object.keys(groupedGames).length" class="h3 p-5 text-center">
                    No records
                </div>
            </div>
        </div>
    </section>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { PendingOdd, Window } from "../../../types/window";
import { Tournament } from "../../../types/tournament";
import GameRow from "./GameRow.vue";
import { Game } from "../../../types/game";
import { empty, groupBy } from "../../../../general/utils/utils";
import {
    PendingOddPayload,
    ToggleSportPayload,
    UpdateWindowPayload,
} from "../../../store/modules/window";

export default Vue.extend({
    name: "MatchesSection",
    components: { GameRow },

    props: {
        window: Object as PropType<Window>,
    },

    computed: {
        tournament(): Tournament {
            return this.window.tournament;
        },

        areAllSportsSelected(): boolean {
            return this.window.selectedSportIds.length === 0;
        },

        groupedGames(): Record<string, Game[]> {
            const filteredGames = this.tournament.games.filter(
                game =>
                    empty(this.window.selectedSportIds) ||
                    this.window.selectedSportIds.includes(game.sport_id),
            );
            return groupBy(filteredGames, game => game.match_time);
        },
    },

    methods: {
        getSportName(sportId: string): string {
            const dict: ReadonlyMap<string, string> = this.$stock.getters["sport/sportDictionary"];
            return dict.get(sportId) ?? String(sportId);
        },

        isSportSelected(sportId: string): boolean {
            return this.window.selectedSportIds.includes(sportId);
        },

        selectAllSports(): void {
            const payload: UpdateWindowPayload = {
                id: this.window.id,
                selectedSportIds: [],
            };
            this.$stock.commit("window/updateWindow", payload);
        },

        toggleSport(sportId: string): void {
            const payload: ToggleSportPayload = {
                windowId: this.window.id,
                sportId,
            };
            this.$stock.commit("window/toggleSport", payload);
        },

        toggleOdd(pendingOdd: PendingOdd): void {
            const payload: PendingOddPayload = {
                windowId: this.window.id,
                tournamentEventId: pendingOdd.tournamentEventId,
                eventId: pendingOdd.eventId,
                type: pendingOdd.type,
            };
            this.$stock.dispatch("window/toggleOdd", payload);
        },
    },
});
</script>
