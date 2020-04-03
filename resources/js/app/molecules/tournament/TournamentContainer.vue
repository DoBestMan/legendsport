<template>
    <section class="tab-content-frm tab-tournament-frm row">
        <section class="col-3 h-100">
            <div class="section bets">
                <div class="title-bar-frm">
                    <span class="title">
                        {{ balance | formatCurrency }}
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
                    <button type="button" class="btn button game-line">Game Line</button>
                    <button type="button" class="btn button game-first-half checked">
                        1st half
                    </button>
                    <button type="button" class="btn button update">Update</button>
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

        <section class="col-3 h-100">
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
                            <div class="value">{{ tournament.starts | toDateTime }}</div>
                        </div>

                        <div class="col-6">
                            <div class="title">In</div>
                            <div class="value">{{ tournament.starts | diffHumanReadable }}</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <div class="title"># Players</div>
                            <div class="value">{{ tournament.players.length }}</div>
                        </div>

                        <div class="col-4">
                            <div class="title">Buy-In</div>
                            <div class="value">{{ tournament.buyIn | formatDollars }}</div>
                        </div>

                        <div class="col-4">
                            <div class="title">Prize pool</div>
                            <div class="value">{{ tournament.prizePoolMoney | formatDollars }}</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="title">Sports</div>
                            <div class="value">{{ sportsNames }}</div>
                        </div>
                    </div>
                </div>

                <PrizePool :tournament="tournament" />
                <TournamentRankTable :players="tournament.players" />
                <ChatContainer :messages="chatMessages" @sendMessage="sendMessage" />
            </div>
        </section>
    </section>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { BetTypeTab, PendingOdd, Window } from "../../types/window";
import { Tournament } from "../../types/tournament";
import TournamentRankTable from "../general/TournamentRankTable.vue";
import {
    PendingOddPayload,
    ToggleSportPayload,
    UpdateWindowPayload,
} from "../../store/modules/window";
import { empty, groupBy } from "../../../general/utils/utils";
import { Game } from "../../types/game";
import GameRow from "./GameRow.vue";
import PendingTab from "./PendingTab.vue";
import StraightTab from "./StraightTab.vue";
import ParlayTab from "./ParlayTab.vue";
import HistoryTab from "./HistoryTab.vue";
import PrizePool from "./PrizePool.vue";
import ChatContainer from "../chat/ChatContainer.vue";
import { ChatMessage } from "../../types/chat";
import { NewChatMessage } from "../../utils/websockets/NewChatMessage";
import { User } from "../../../general/types/user";

export default Vue.extend({
    name: "TournamentContainer",
    components: {
        ChatContainer,
        GameRow,
        HistoryTab,
        ParlayTab,
        PendingTab,
        PrizePool,
        StraightTab,
        TournamentRankTable,
    },

    props: {
        window: Object as PropType<Window>,
    },

    computed: {
        betTabs(): BetTypeTab[] {
            return Object.values(BetTypeTab);
        },

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

        sportsNames(): string {
            return this.tournament.sportIds.map(this.getSportName).join(", ") || "n/a";
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

        chatMessages(): ChatMessage[] {
            const userIds = new Set(this.tournament.players.map(player => player.userId));

            return this.$stock.state.chat.messages
                .filter(message => message.tournamentId === this.tournament.id)
                .map(message => ({
                    ...message,
                    isParticipant: userIds.has(message.userId),
                }));
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

        isSportSelected(sportId: number): boolean {
            return this.window.selectedSportIds.includes(sportId);
        },

        getSportName(sportId: number): string {
            const dict: ReadonlyMap<number, string> = this.$stock.getters["sport/sportDictionary"];
            return dict.get(sportId) ?? String(sportId);
        },

        selectAllSports(): void {
            const payload: UpdateWindowPayload = {
                id: this.window.id,
                selectedSportIds: [],
            };
            this.$stock.commit("window/updateWindow", payload);
        },

        toggleSport(sportId: number): void {
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

        sendMessage(message: string) {
            this.$echo.sendEvent(new NewChatMessage(this.tournament.id, message), this.user?.token);
        },
    },
});
</script>
