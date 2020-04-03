import { Module } from "vuex";
import { RootState } from "../types";
import { ChatMessage } from "../../types/chat";

export interface ChatState {
    messages: ChatMessage[];
}

const module: Module<ChatState, RootState> = {
    namespaced: true,
    state: {
        messages: [],
    },
    mutations: {
        addMessage(state, message: ChatMessage) {
            state.messages.push(message);
        },
    },
};

export default module;
