import { Module } from "vuex";
import { RootState } from "../types";
import { BettingType, getTabs, StorableTab } from "../../utils/local-storage/LocalStorageManager";
import { Tab } from "../../types/tab";

export interface TabsState {
    _tabs: StorableTab[];
}

export type UpdateTabPayload = StorableTab;

export interface SelectTabSportPayload {
    id: number;
    sportId: number;
}

const module: Module<TabsState, RootState> = {
    namespaced: true,

    state: {
        _tabs: getTabs(),
    },

    getters: {
        tabs(state, _getters, rootState): Tab[] {
            const tournamentDict = new Map(
                rootState.tournamentList.tournaments.map(tournament => [tournament.id, tournament]),
            );

            return state._tabs
                .filter(tab => tournamentDict.has(tab.id))
                .map(tab => {
                    const tournament = tournamentDict.get(tab.id)!;

                    return {
                        selectedBetting: BettingType.Pending,
                        selectedSportIds: [],
                        ...tab,
                        tournament,
                    };
                });
        },
    },

    mutations: {
        openTab(state, payload: number) {
            const tabExists = !!state._tabs.find(tab => tab.id === payload);
            if (tabExists) {
                return;
            }

            state._tabs.push({
                id: payload,
                selectedBetting: BettingType.Pending,
            });
        },

        closeTab(state, payload: number) {
            state._tabs = state._tabs.filter(tab => tab.id !== payload);
        },

        updateTab(state, payload: UpdateTabPayload) {
            state._tabs = state._tabs.map(tab => {
                if (tab.id !== payload.id) {
                    return tab;
                }

                return { ...tab, ...payload };
            });
        },

        selectTabSport(state, payload: SelectTabSportPayload) {
            state._tabs = state._tabs.map(tab => {
                if (tab.id !== payload.id) {
                    return tab;
                }

                if (tab.selectedSportIds?.includes(payload.sportId)) {
                    return {
                        ...tab,
                        selectedSportIds: tab.selectedSportIds.filter(
                            sportId => sportId !== payload.sportId,
                        ),
                    };
                }

                return {
                    ...tab,
                    selectedSportIds: [...(tab.selectedSportIds ?? []), payload.sportId],
                };
            });
        },
    },
};

export default module;
