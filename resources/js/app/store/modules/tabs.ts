import { Module } from "vuex";
import { RootState } from "../types";
import { Tournament } from "../../types/tournament";
import { getTabs, saveTabs, StorableTab } from "../../local-storage/LocalStorageManager";

export interface Tab {
    id: number;
    tournament: Tournament;
}

export interface TabsState {
    _tabs: StorableTab[];
}

const module: Module<TabsState, RootState> = {
    namespaced: true,

    state: {
        _tabs: [],
    },

    getters: {
        tabs(state, _getters, rootState): Tab[] {
            const tournamentDict = new Map(
                rootState.tournamentList.tournaments.map(tournament => [tournament.id, tournament]),
            );

            return state._tabs
                .filter(tab => tournamentDict.has(tab.id))
                .map(tab => ({
                    ...tab,
                    tournament: tournamentDict.get(tab.id)!,
                }));
        },
    },

    mutations: {
        initialise(state) {
            state._tabs = getTabs();
        },

        openTab(state, payload: number) {
            const tabExists = !!state._tabs.find(tab => tab.id === payload);
            if (tabExists) {
                return;
            }

            state._tabs.push({
                id: payload,
            });
            saveTabs(state._tabs);
        },

        closeTab(state, payload: number) {
            state._tabs = state._tabs.filter(tab => tab.id !== payload);
            saveTabs(state._tabs);
        },
    },
};

export default module;
