import { Module } from "vuex";
import { RootState } from "../types";
import {
    BetTypeTab,
    getWindows,
    StorableWindow,
} from "../../utils/local-storage/LocalStorageManager";
import { Window } from "../../types/window";

export interface WindowState {
    _windows: StorableWindow[];
}

export type UpdateWindowPayload = StorableWindow;

export interface SelectSportPayload {
    id: number;
    sportId: number;
}

const module: Module<WindowState, RootState> = {
    namespaced: true,

    state: {
        _windows: getWindows(),
    },

    getters: {
        windows(state, _getters, rootState): Window[] {
            const tournamentDict = new Map(
                rootState.tournamentList.tournaments.map(tournament => [tournament.id, tournament]),
            );

            return state._windows
                .filter(window => tournamentDict.has(window.id))
                .map(tab => {
                    const tournament = tournamentDict.get(tab.id)!;

                    return {
                        selectedBetting: BetTypeTab.Pending,
                        selectedSportIds: [],
                        ...tab,
                        tournament,
                    };
                });
        },
    },

    mutations: {
        openWindow(state, payload: number) {
            const tabExists = !!state._windows.find(window => window.id === payload);
            if (tabExists) {
                return;
            }

            state._windows.push({
                id: payload,
                selectedBetting: BetTypeTab.Pending,
            });
        },

        closeWindow(state, payload: number) {
            state._windows = state._windows.filter(window => window.id !== payload);
        },

        updateWindow(state, payload: UpdateWindowPayload) {
            state._windows = state._windows.map(window => {
                if (window.id !== payload.id) {
                    return window;
                }

                return { ...window, ...payload };
            });
        },

        selectSport(state, payload: SelectSportPayload) {
            state._windows = state._windows.map(window => {
                if (window.id !== payload.id) {
                    return window;
                }

                if (window.selectedSportIds?.includes(payload.sportId)) {
                    return {
                        ...window,
                        selectedSportIds: window.selectedSportIds.filter(
                            sportId => sportId !== payload.sportId,
                        ),
                    };
                }

                return {
                    ...window,
                    selectedSportIds: [...(window.selectedSportIds ?? []), payload.sportId],
                };
            });
        },
    },
};

export default module;
