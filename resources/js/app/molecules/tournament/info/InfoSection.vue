<template>
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

                <RegisterNowButton
                    v-if="!isRegistered"
                    class="mb-3 mt-1"
                    :tournament="tournament"
                />
            </div>

            <PrizePool :tournament="tournament" />
            <TournamentRankTable :players="tournament.players" />
            <ChatContainer :messages="chatMessages" @sendMessage="sendMessage" />
        </div>
    </section>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { Window } from "../../../types/window";
import { Tournament } from "../../../types/tournament";
import ChatContainer from "../../chat/ChatContainer.vue";
import TournamentRankTable from "../../general/TournamentRankTable.vue";
import PrizePool from "./PrizePool.vue";
import { ChatMessage } from "../../../types/chat";
import { NewChatMessage } from "../../../utils/websockets/NewChatMessage";
import { User, UserPlayer } from "../../../../general/types/user";
import RegisterNowButton from "../../../components/RegisterNowButton.vue";

export default Vue.extend({
    name: "InfoSection",
    components: { PrizePool, RegisterNowButton, TournamentRankTable, ChatContainer },

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

        isRegistered(): boolean {
            const playersDict: ReadonlyMap<number, UserPlayer> = this.$stock.getters[
                "user/playersDictByTournament"
            ];
            return playersDict.has(this.tournament.id);
        },

        sportsNames(): string {
            return this.tournament.sportIds.map(this.getSportName).join(", ") || "n/a";
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
    },

    methods: {
        getSportName(sportId: number): string {
            const dict: ReadonlyMap<number, string> = this.$stock.getters["sport/sportDictionary"];
            return dict.get(sportId) ?? String(sportId);
        },

        sendMessage(message: string) {
            this.$echo.sendEvent(new NewChatMessage(this.tournament.id, message), this.user?.token);
        },
    },
});
</script>
