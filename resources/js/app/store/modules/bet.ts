import { Module } from "vuex";
import { RootState } from "../types";
import { Bet } from "../../types/bet";
import { AxiosError } from "axios";

export interface BetState {
    isLoading: boolean;
    isLoaded: boolean;
    error: AxiosError | null;
    bets: Bet[];
}

const module: Module<BetState, RootState> = {
    namespaced: true,

    state: {
        isLoading: false,
        isLoaded: false,
        error: null,
        bets: [],
    },

    mutations: {
        markAsLoading(state) {
            state.isLoading = true;
        },

        markAsLoaded(state, bets: Bet[]) {
            state.isLoading = false;
            state.error = null;
            state.isLoaded = true;
            state.bets = bets;
        },

        markAsFailed(state, e: AxiosError) {
            state.isLoading = false;
            state.error = e;
        },
    },

    actions: {
        async load({ state, dispatch }) {
            if (!state.bets.length) {
                await dispatch("reload");
            }
        },

        async reload({ commit, rootState }) {
            commit("markAsLoading");

            try {
                const bets = await rootState.api.getBets();
                commit("markAsLoaded", bets);
            } catch (e) {
                commit("markAsFailed", e);
            }
        },
    },
};

export default module;
