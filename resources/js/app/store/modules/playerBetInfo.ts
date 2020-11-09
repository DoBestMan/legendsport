import { TournamentPlayer } from "resources/js/general/types/user";
import { Module } from "vuex";
import { RootState } from "../types";

export interface PlayerBetInfoState {
    playerID: number | null;
    player: TournamentPlayer | null;
    selectedBetTypeTab: PlayerBetTypeTab;
}

export enum PlayerBetTypeTab {
    Pending = "Pending",
    History = "History",
}

const module: Module<PlayerBetInfoState, RootState> = {
    namespaced: true,

    state: {
        playerID: null,
        player: null,
        selectedBetTypeTab: PlayerBetTypeTab.Pending,
    },

    getters: {
        getTournamentId(state): number | null {
            return state.player ? state.player.tournamentId : null;
        },

        getPlayer(state): TournamentPlayer | null {
            return state.player;
        },
        getSelectedPlayerBetTypeTab(state): PlayerBetTypeTab {
            return state.selectedBetTypeTab;
        },
    },

    mutations: {
        markPlayerBetInfoSelection(state, payload) {
            state.playerID = payload.playerID;
            state.player = payload.player;
        },
        resetPlayerBetSelection(state) {
            state.playerID = null;
            state.player = null;
        },
        updateSelectedPlayerBetTypeTab(state, payload) {
            state.selectedBetTypeTab = payload.selectedPlayerBetTypeTab;
        },
    },
    actions: {},
};

export default module;
