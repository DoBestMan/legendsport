import { Module } from "vuex";
import { RootState } from "../types";
import { DeepReadonly } from "../../../general/types/types";
import { Odd } from "../../types/odd";

export interface OddState {
    isLoading: boolean;
    isLoaded: boolean;
    isFailed: boolean;
    odds: Map<string, DeepReadonly<Odd>>;
}

const module: Module<OddState, RootState> = {
    namespaced: true,

    state: {
        isLoading: false,
        isLoaded: false,
        isFailed: false,
        odds: new Map(),
    },

    getters: {
        oddDictionary(state): ReadonlyMap<string, Odd> {
            return state.odds;
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
            state.odds = new Map(odds.map(odd => [odd.external_id, odd]));
        },

        oddsUpdate(state, odds: Odd) {
            state.odds.set(odds.external_id, odds);
        },

        markAsFailed(state) {
            state.isLoading = false;
            state.isFailed = true;
        },
    },

    actions: {
        async load({ state, dispatch }) {
            if (!state.odds.size) {
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
