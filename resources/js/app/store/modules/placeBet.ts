import { Module } from "vuex";
import omit from "lodash.omit";
import { RootState } from "../types";
import { PlaceParlayBetBody, PlaceStraightBetBody } from "../../api/Api";
import { AxiosError } from "axios";

export interface PlaceStraightBetPayload extends PlaceStraightBetBody {
    tournamentId: number;
}

export interface PlaceParlayBetPayload extends PlaceParlayBetBody {
    tournamentId: number;
}

export interface PlaceBetState {
    isLoading: boolean;
    error: AxiosError | null;
}

const module: Module<PlaceBetState, RootState> = {
    namespaced: true,

    state: {
        isLoading: false,
        error: null,
    },

    mutations: {
        markAsLoading(state) {
            state.isLoading = true;
        },

        markAsLoaded(state) {
            state.isLoading = false;
            state.error = null;
        },

        markAsFailed(state, error: AxiosError) {
            state.isLoading = false;
            state.error = error;
        },
    },

    actions: {
        async placeStraight({ commit, rootState }, payload: PlaceStraightBetPayload) {
            commit("markAsLoading");
            commit("loader/show", null, { root: true });

            try {
                await rootState.api.placeStraightBet(
                    payload.tournamentId,
                    omit(payload, ["tournamentId"]),
                );
                commit("markAsLoaded");
            } catch (e) {
                commit("markAsFailed", e);
            } finally {
                commit("loader/hide", null, { root: true });
            }
        },

        async placeParlay({ commit, rootState }, payload: PlaceParlayBetPayload) {
            commit("markAsLoading");
            commit("loader/show", null, { root: true });

            try {
                await rootState.api.placeParlayBet(
                    payload.tournamentId,
                    omit(payload, ["tournamentId"]),
                );
                commit("markAsLoaded");
            } catch (e) {
                commit("markAsFailed", e);
            } finally {
                commit("loader/hide", null, { root: true });
            }
        },
    },
};

export default module;
