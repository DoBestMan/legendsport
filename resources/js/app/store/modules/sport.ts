import { Module } from "vuex";
import { RootState } from "../types";
import { Sport } from "../../../general/types/sport";

export interface SportState {
    isLoading: boolean;
    isLoaded: boolean;
    isFailed: boolean;
    sports: Sport[];
}

const module: Module<SportState, RootState> = {
    namespaced: true,

    state: {
        isLoading: false,
        isLoaded: false,
        isFailed: false,
        sports: [],
    },

    getters: {
        sportDictionary(state): ReadonlyMap<string, string> {
            return new Map(state.sports.map(sport => [sport.id, sport.name]));
        },
    },

    mutations: {
        markAsLoading(state) {
            state.isLoading = true;
        },

        markAsLoaded(state, sports: Sport[]) {
            state.isLoading = false;
            state.isFailed = false;
            state.isLoaded = true;
            state.sports = sports;
        },

        markAsFailed(state) {
            state.isLoading = false;
            state.isFailed = true;
        },
    },

    actions: {
        async load({ state, dispatch }) {
            if (!state.sports.length) {
                await dispatch("reload");
            }
        },

        async reload({ commit, rootState }) {
            commit("markAsLoading");

            try {
                const sports = await rootState.api.getSports();
                commit("markAsLoaded", sports);
            } catch (e) {
                commit("markAsFailed");
            }
        },
    },
};

export default module;
