import { Module } from "vuex";
import { RootState } from "../types";

export interface LoaderState {
    isVisible: boolean;
}

const module: Module<LoaderState, RootState> = {
    namespaced: true,
    state: {
        isVisible: false,
    },
    mutations: {
        show(state) {
            state.isVisible = true;
        },

        hide(state) {
            state.isVisible = false;
        },
    },
};

export default module;
