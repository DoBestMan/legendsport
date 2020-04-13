import { Module } from "vuex";
import { RootState } from "../types";
import { DeepReadonly } from "../../../general/types/types";
import { Result } from "../../types/result";

export interface ResultState {
    isLoading: boolean;
    isLoaded: boolean;
    error: Error | null;
    results: Array<DeepReadonly<Result>>;
}

const module: Module<ResultState, RootState> = {
    namespaced: true,

    state: {
        isLoading: false,
        isLoaded: false,
        error: null,
        results: [],
    },

    getters: {
        resultDictionary(state): ReadonlyMap<string, Result> {
            return new Map(state.results.map(result => [result.externalId, result]));
        },
    },

    mutations: {
        markAsLoading(state) {
            state.isLoading = true;
        },

        markAsLoaded(state, results: Result[]) {
            state.isLoading = false;
            state.error = null;
            state.isLoaded = true;
            state.results = results;
        },

        markAsFailed(state, error) {
            state.isLoading = false;
            state.error = error;
        },
    },

    actions: {
        async load({ state, dispatch }) {
            if (!state.results.length) {
                await dispatch("reload");
            }
        },

        async reload({ commit, rootState }) {
            commit("markAsLoading");

            try {
                const results = await rootState.api.getResults();
                commit("markAsLoaded", results);
            } catch (e) {
                commit("markAsFailed");
            }
        },
    },
};

export default module;
