import { Module } from "vuex";
import { RootState } from "../types";

export enum AuthModalTab {
    SignIn = "sign-in",
    SignUp = "sign-up",
}

export interface AuthModalState {
    isVisible: boolean;
    tab: AuthModalTab;
}

const module: Module<AuthModalState, RootState> = {
    namespaced: true,

    state: {
        isVisible: false,
        tab: AuthModalTab.SignIn,
    },

    mutations: {
        open(state, tab: AuthModalTab): void {
            state.tab = tab;
            state.isVisible = true;
        },

        hide(state): void {
            state.isVisible = false;
        },

        updateTab(state, tab: AuthModalTab): void {
            state.tab = tab;
        },

        updateVisibility(state, isVisible: boolean): void {
            state.isVisible = isVisible;
        },
    },
};

export default module;
