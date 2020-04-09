import { Module } from "vuex";
import { RootState } from "../types";
import { DeepReadonly } from "../../../general/types/types";
import { Odd } from "../../types/odd";

export interface OddState {
    isLoading: boolean;
    isLoaded: boolean;
    isFailed: boolean;
    odds: Array<DeepReadonly<Odd>>;
}

const module: Module<OddState, RootState> = {
    namespaced: true,

    state: {
        isLoading: false,
        isLoaded: false,
        isFailed: false,
        odds: [],
    },

    getters: {
        oddDictionary(state): ReadonlyMap<string, Odd> {
            return new Map(state.odds.map(odd => [odd.id, odd]));
        },
    },

    mutations: {
        markAsLoading(state) {
            state.isLoading = true;
        },

        markAsLoaded(state, odds: Odd[]) {
            state.isLoading = false;
            state.isFailed = false;
            state.isLoaded = true;
            state.odds = odds;
        },

        markAsFailed(state) {
            state.isLoading = false;
            state.isFailed = true;
        },
    },

    actions: {
        async load({ state, dispatch }) {
            if (!state.odds.length) {
                await dispatch("reload");
            }
        },

        async reload({ commit, rootState }) {
            commit("markAsLoading");

            try {
                const odds = await rootState.api.getOdds();
                commit("markAsLoaded", odds);
            } catch (e) {
                commit("markAsFailed");
            }
        },
    },
};

export default module;
