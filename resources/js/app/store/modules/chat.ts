import { Module } from "vuex";
import { RootState } from "../types";
import { ChatMessage } from "../../types/chat";
import echo from "../../echo";

export interface ChatState {
    messages: ChatMessage[];
    listenedTournamentsIds: number[];
}

const module: Module<ChatState, RootState> = {
    namespaced: true,

    state: {
        messages: [],
        listenedTournamentsIds: [],
    },

    mutations: {
        markTournamentAsListened(state, tournamentId: number) {
            state.listenedTournamentsIds.push(tournamentId);
        },

        addMessage(state, message: ChatMessage) {
            state.messages.push(message);
        },
    },

    actions: {
        async listenOnNewMessages({ commit, state }, tournamentId: number) {
            if (state.listenedTournamentsIds.includes(tournamentId)) {
                return;
            }

            commit("markTournamentAsListened", tournamentId);
            echo.channel(`chat.tournaments.${tournamentId}`).listen(".message", (e: ChatMessage) =>
                commit("addMessage", e),
            );
        },
    },
};

export default module;
