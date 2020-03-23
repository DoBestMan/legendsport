<template>
    <section class="tab-content-frm tab-tournament-frm row">
        <section class="col-3">
            <section class="section bets">
                <div class="title-bar-frm">
                    <span class="title">0</span>
                </div>

                <div class="tabs-frm">
                    <div class="tab-frm" v-for="bettingType in bettingTypeOptions">
                        <button
                            type="button"
                            class="tab"
                            :class="{ active: isBettingSelected(bettingType) }"
                            @click="selectBetting(bettingType)"
                        >
                            {{ bettingType }}
                        </button>
                        <span class="separator">|</span>
                    </div>
                </div>

                <div v-if="isBettingSelected(BettingType.Pending)" class="tab-content-frm">
                    <div class="event-frm">
                        <div class="data-frm">
                            <div class="type-bet">Straight</div>

                            <div class="text">[datetime]</div>
                            <div class="text game-frm">
                                <div class="text team">Miami Dolphins</div>
                                <div class="text score">0</div>
                                <div class="text vs">@</div>
                                <div class="text team">Washington Nationals</div>
                                <div class="text score">0</div>
                            </div>
                            <div class="text">[team/+odds/result]</div>
                        </div>

                        <div class="bet-frm">
                            <div>Bet: $ 0</div>

                            <div>Win: $ 0</div>
                        </div>
                    </div>

                    <div class="event-frm">
                        <div class="data-frm">
                            <div class="type-bet">Parlay</div>

                            <div class="text">[datetime]</div>
                            <div class="text game-frm">
                                <div class="text team">Team C</div>
                                <div class="text score">0</div>
                                <div class="text vs">@</div>
                                <div class="text team">Team D</div>
                                <div class="text score">0</div>
                            </div>
                            <div class="text">[team/+odds/result]</div>
                        </div>

                        <div class="data-frm">
                            <div class="text">[datetime]</div>
                            <div class="text game-frm">
                                <div class="text team">Team C</div>
                                <div class="text score">0</div>
                                <div class="text vs">@</div>
                                <div class="text team">Team D</div>
                                <div class="text score">0</div>
                            </div>
                            <div class="text">[team/+odds/result]</div>
                        </div>

                        <div class="bet-frm">
                            <div>Bet: $0</div>

                            <div>Win: $0</div>
                        </div>
                    </div>
                </div>

                <div v-if="isBettingSelected(BettingType.History)" class="tab-content-frm">
                    <div class="event-frm">
                        <div class="data-frm">
                            <div class="type-bet">Straight</div>

                            <div class="text">[datetime]</div>
                            <div class="text game-frm">
                                <div class="text team">Miami Dolphins</div>
                                <div class="text score">0</div>
                                <div class="text vs">@</div>
                                <div class="text team">Washington Nationals</div>
                                <div class="text score">0</div>
                            </div>
                            <div class="text">[team/+odds/result]</div>
                        </div>

                        <div class="bet-frm">
                            <div>Bet: $ 0</div>

                            <div>Win: $ 0</div>
                        </div>

                        <div class="result lost"><i class="icon fas fa-frown"></i> YOU LOST!</div>
                    </div>

                    <div class="event-frm">
                        <div class="data-frm">
                            <div class="type-bet">Parlay</div>

                            <div class="text">[datetime]</div>
                            <div class="text game-frm">
                                <div class="text team">Team C</div>
                                <div class="text score">0</div>
                                <div class="text vs">@</div>
                                <div class="text team">Team D</div>
                                <div class="text score">0</div>
                            </div>
                            <div class="text">[team/+odds/result]</div>
                        </div>

                        <div class="data-frm">
                            <div class="text">[datetime]</div>
                            <div class="text game-frm">
                                <div class="text team">Team C</div>
                                <div class="text score">0</div>
                                <div class="text vs">@</div>
                                <div class="text team">Team D</div>
                                <div class="text score">0</div>
                            </div>
                            <div class="text">[team/+odds/result]</div>
                        </div>

                        <div class="bet-frm">
                            <div>Bet: $ 0</div>

                            <div>Win: $ 0</div>
                        </div>

                        <div class="result win">
                            <i class="icon fas fa-laugh-beam"></i> YOU WON!
                        </div>
                    </div>
                </div>
            </section>
        </section>

        <section class="col-6 h-100">
            <div class="section matches">
                <div class="tabs-frm">
                    <div class="tab-frm">
                        <button
                            type="button"
                            class="tab"
                            :class="{ active: areAllSportsSelected }"
                            @click="selectAllSports"
                        >
                            All
                        </button>
                        <span class="separator">|</span>
                    </div>

                    <div class="tab-frm" v-for="sportId in tournament.sport_ids">
                        <button
                            type="button"
                            class="tab"
                            :class="{ active: isSportSelected(sportId) }"
                            @click="selectSport(sportId)"
                        >
                            {{ getSportName(sportId) }}
                        </button>
                        <span class="separator">|</span>
                    </div>
                </div>

                <div class="actions-frm">
                    <button type="button" class="button game-line">Game Line</button>
                    <button type="button" class="button game-first-half checked">1st half</button>
                    <button type="button" class="button update">Update</button>
                </div>

                <div class="tables-frm overflow-auto">
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
                            <GameRow :game="game" v-for="game in games" :key="game.id" />
                        </tbody>
                    </table>
                    <div v-if="!groupedGames.length" class="h3 p-5 text-center">
                        No records
                    </div>
                </div>
            </div>
        </section>

        <section class="col-3">
            <div class="section info">
                <div class="title-bar-frm">
                    <div class="img-frm">
                        <div class="img">
                            <i class="icon fas fa-football-ball"></i>
                        </div>
                    </div>

                    <div class="title-frm">
                        <div class="title">{{ tournament.name }}</div>
                    </div>

                    <div class="status-frm">
                        <div class="status">{{ tournament.state }}</div>
                    </div>
                </div>

                <div class="tournament-frm">
                    <div class="row">
                        <div class="col-6">
                            <div class="title">Start time</div>
                            <div class="value">{{ tournament.starts || "n/a" }}</div>
                        </div>

                        <div class="col-6">
                            <div class="title">In</div>
                            <div class="value">0 hours</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <div class="title"># Players</div>
                            <div class="value">{{ tournament.players.length }}</div>
                        </div>

                        <div class="col-4">
                            <div class="title">Buy-In</div>
                            <div class="value">{{ tournament.buy_in || "n/a" }}</div>
                        </div>

                        <div class="col-4">
                            <div class="title">Prize pool</div>
                            <div class="value">$1,000</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="title">Sports</div>
                            <div class="value">{{ sportsNames }}</div>
                        </div>
                    </div>
                </div>

                <table class="awards table">
                    <thead class="thead">
                        <tr class="tr">
                            <th class="th col-position" scope="col">Position</th>
                            <th class="th col-prize" scope="col">Prize</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        <template v-for="i in 3">
                            <tr class="tr">
                                <td class="td col-position">{{ i }}</td>
                                <td class="td col-prize">${{ 900 / i }}</td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <TournamentRankTable :players="tournament.players" />
            </div>
        </section>
    </section>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import moment from "moment";
import { Tab } from "../../types/tab";
import { BettingType } from "../../utils/local-storage/LocalStorageManager";
import { Tournament } from "../../types/tournament";
import TournamentRankTable from "../general/TournamentRankTable.vue";
import { DeepReadonly } from "../../../general/types/types";
import { SelectTabSportPayload, UpdateTabPayload } from "../../store/modules/tabs";
import { empty, groupBy } from "../../../general/utils/utils";
import { Game } from "../../types/game";
import GameRow from "./GameRow.vue";

export default Vue.extend({
    name: "TournamentContainer",
    components: { GameRow, TournamentRankTable },
    filters: {
        toDateTime: (value: string) => moment(value).format("MMM, DD [AT] HH:mm [ET]"),
    },

    props: {
        tab: Object as PropType<DeepReadonly<Tab>>,
    },

    computed: {
        bettingTypeOptions(): BettingType[] {
            return Object.values(BettingType);
        },

        tournament(): DeepReadonly<Tournament> {
            return this.tab.tournament;
        },

        sportsNames(): string {
            return this.tournament.sport_ids.map(this.getSportName).join(", ") || "n/a";
        },

        areAllSportsSelected(): boolean {
            return this.tab.selectedSportIds.length === 0;
        },

        groupedGames(): DeepReadonly<Record<string, Game[]>> {
            const filteredGames = this.tournament.games.filter(
                game =>
                    empty(this.tab.selectedSportIds) ||
                    this.tab.selectedSportIds.includes(game.sport_id),
            );
            return groupBy(filteredGames, game => game.match_time);
        },

        BettingType(): typeof BettingType {
            return BettingType;
        },
    },

    methods: {
        isBettingSelected(type: BettingType): boolean {
            return this.tab.selectedBetting === type;
        },

        selectBetting(type: BettingType): void {
            const payload: UpdateTabPayload = {
                id: this.tab.id,
                selectedBetting: type,
            };
            this.$store.commit("tabs/updateTab", payload);
        },

        isSportSelected(sportId: number): boolean {
            return this.tab.selectedSportIds.includes(sportId);
        },

        selectAllSports(): void {
            const payload: UpdateTabPayload = {
                id: this.tab.id,
                selectedSportIds: [],
            };
            this.$store.commit("tabs/updateTab", payload);
        },

        selectSport(sportId: number): void {
            const payload: SelectTabSportPayload = {
                id: this.tab.id,
                sportId,
            };
            this.$store.commit("tabs/selectTabSport", payload);
        },

        getSportName(sportId: number): string {
            const dict: ReadonlyMap<number, string> = this.$store.getters["sport/sportDictionary"];
            return dict.get(sportId) ?? String(sportId);
        },
    },
});
</script>
