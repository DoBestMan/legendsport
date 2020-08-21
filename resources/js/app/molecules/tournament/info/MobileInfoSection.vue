<template>
    <div class="layout__content layout__content--home">
        <div class="layout__content__sidebar layout__content__sidebar--active">
            <div class="layout__content__sidebar__games">
                <TournamentInfo :tournament="window.tournament" />

                <div
                    class="layout__content__sidebar__chat"
                    style="position: unset; margin-bottom: 25px;"
                >
                    <div class="layout__content__sidebar__chat__cta">
                        <div class="layout__content__sidebar__chat__cta__title">
                            <i class="icon icon--micro icon--chat icon--color--light-1 m--r--2"></i>
                            CHAT({{ chatMessages.length }})
                        </div>
                        <div
                            class="layout__content__sidebar__chat__cta__action"
                            @click="handleChatExpand"
                        >
                            <i class="icon icon--micro icon--arrow-right icon--color--light-2"></i>
                        </div>
                    </div>
                </div>

                <MobileChatContainer
                    v-if="isChatExpanded"
                    :messages="chatMessages"
                    @sendMessage="sendMessage"
                    @handleChatExpand="handleChatExpand"
                />

                <InfoDetailSection :tournament="window.tournament" :window="window" />

                <div
                    class="layout__content__sidebar__chat"
                    style="border: none;"
                    v-show="!isChatExpanded"
                >
                    <RegisterNowButton
                        v-if="!isRegistered() && canRegister"
                        className="tournament--mobile__offer"
                        style="width: 100%; border: none;"
                        :tournament="window.tournament"
                    />
                    <button
                        v-else
                        class="tournament--mobile__offer"
                        style="width: 100%; border: none;"
                        @click="viewOdds"
                    >
                        VIEW ODDS
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import { Window } from "../../../types/window";
import MobileChatContainer from "../../chat/MobileChatContainer.vue";
import InfoDetailSection from "./InfoDetailSection.vue";
import TournamentRankTable from "../../general/TournamentRankTable.vue";
import { ChatMessage } from "../../../types/chat";
import { NewChatMessage } from "../../../utils/websockets/NewChatMessage";
import { User } from "../../../../general/types/user";
import RegisterNowButton from "../../../components/RegisterNowButton.vue";
import TournamentInfo from "../../general/TournamentInfo.vue";
import { asNumber } from "../../../../general/utils/utils";
import { DeepReadonly } from "../../../../general/types/types";
import { UserPlayer } from "../../../../general/types/user";
import { TournamentState } from "../../../../general/types/tournament";

export default Vue.extend({
    name: "MobileInfoSection",
    components: {
        InfoDetailSection,
        RegisterNowButton,
        TournamentInfo,
        TournamentRankTable,
        MobileChatContainer,
    },

    data() {
        return {
            isChatExpanded: false,
        };
    },

    computed: {
        tournamentId(): number {
            return asNumber(this.$route.params.tournamentId)!;
        },

        window(): DeepReadonly<Window> | null {
            return (
                this.$stock.getters["window/windows"].find(
                    (window: Window) => window.id === this.tournamentId,
                ) ?? null
            );
        },

        user(): User | null {
            return this.$stock.state.user.user;
        },

        chatMessages(): ChatMessage[] {
            const userIds = new Set(this.window?.tournament.players.map(player => player.userId));

            return this.$stock.state.chat.messages
                .filter(message => message.tournamentId === this.tournamentId)
                .map(message => ({
                    ...message,
                    isParticipant: userIds.has(message.userId),
                }));
        },

        canRegister(): boolean {
            if (!this.window?.tournament) return false;
            return [TournamentState.Registering, TournamentState.LateRegistering].includes(
                this.window?.tournament.state,
            );
        },
    },

    methods: {
        sendMessage(message: string) {
            this.$echo.sendEvent(new NewChatMessage(this.tournamentId, message), this.user?.token);
        },

        handleChatExpand(): void {
            this.isChatExpanded = !this.isChatExpanded;
        },

        isRegistered(): boolean {
            const playersDict: ReadonlyMap<number, UserPlayer> = this.$stock.getters[
                "user/playersDictByTournament"
            ];
            return playersDict.has(this.tournamentId);
        },

        viewOdds(): void {
            this.$stock.commit("window/openWindow", this.tournamentId);
            this.$router.push(`/tournaments/${this.tournamentId}`);
        },
    },
});
</script>
