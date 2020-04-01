import { Module } from "vuex";
import { RootState } from "../types";
import { User } from "../../../general/types/user";
import { UNAUTHORIZED } from "http-status-codes";
import { AxiosError } from "axios";

export interface UserState {
    isLoading: boolean;
    isFailed: boolean;
    user: User | null;
}

const module: Module<UserState, RootState> = {
    namespaced: true,

    state: {
        isLoading: false,
        isFailed: false,
        user: null,
    },

    mutations: {
        markAsLoading(state) {
            state.isLoading = true;
        },

        markAsLoaded(state, user: User) {
            state.isLoading = false;
            state.isFailed = false;
            state.user = user;
        },

        markAsFailed(state, e: AxiosError) {
            if (e.response?.status === UNAUTHORIZED) {
                state.user = null;
            }

            state.isLoading = false;
            state.isFailed = true;
        },

        unsetUser(state) {
            state.isLoading = false;
            state.isFailed = false;
            state.user = null;
        },
    },

    actions: {
        async load({ state, dispatch }) {
            if (!state.user) {
                await dispatch("reload");
            }
        },

        async reload({ commit, rootState }) {
            commit("markAsLoading");

            try {
                const user = await rootState.api.getMe();
                commit("markAsLoaded", user);
            } catch (e) {
                commit("markAsFailed", e);
            }
        },

        async logout({ commit, rootState }) {
            try {
                await rootState.api.logout();
                commit("unsetUser");
            } catch (e) {
                console.error(e);
            }
        },
    },
};

export default module;
