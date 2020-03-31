import { Module } from "vuex";
import { RootState } from "../types";
import { getWindows } from "../../utils/local-storage/LocalStorageManager";
import { BetTypeTab, PendingOdd, StorableWindow, Window } from "../../types/window";
import { DeepReadonlyArray } from "../../../general/types/types";

export interface WindowState {
    _windows: StorableWindow[];
}

export type UpdateWindowPayload = Partial<StorableWindow> & Pick<StorableWindow, "id">;

export interface ToggleSportPayload {
    windowId: number;
    sportId: number;
}

export interface PendingOddPayload extends PendingOdd {
    windowId: number;
}

export interface UpdateOddsBetPayload {
    windowId: number;
    bet: number;
}

const pendingOddsMatch = (a: PendingOdd, b: PendingOdd): boolean =>
    a.eventId === b.eventId && a.type === b.type;

const module: Module<WindowState, RootState> = {
    namespaced: true,

    state: {
        _windows: getWindows(),
    },

    getters: {
        windows(state, _getters, rootState): DeepReadonlyArray<Window> {
            const tournamentDict = new Map(
                rootState.tournamentList.tournaments.map(tournament => [tournament.id, tournament]),
            );

            return state._windows
                .filter(window => tournamentDict.has(window.id))
                .map(tab => {
                    const tournament = tournamentDict.get(tab.id)!;

                    return {
                        pendingOdds: [],
                        selectedBetTypeTab: BetTypeTab.Pending,
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
                pendingOdds: [],
                selectedBetTypeTab: BetTypeTab.Pending,
                selectedSportIds: [],
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

        toggleSport(state, payload: ToggleSportPayload) {
            state._windows = state._windows.map(window => {
                if (window.id !== payload.windowId) {
                    return window;
                }

                if (window.selectedSportIds.includes(payload.sportId)) {
                    return {
                        ...window,
                        selectedSportIds: window.selectedSportIds.filter(
                            sportId => sportId !== payload.sportId,
                        ),
                    };
                }

                return {
                    ...window,
                    selectedSportIds: [...window.selectedSportIds, payload.sportId],
                };
            });
        },

        toggleOdd(state, payload: PendingOddPayload) {
            state._windows = state._windows.map(window => {
                if (window.id !== payload.windowId) {
                    return window;
                }

                if (window.pendingOdds.find(item => pendingOddsMatch(item, payload))) {
                    return {
                        ...window,
                        pendingOdds: window.pendingOdds.filter(
                            item => !pendingOddsMatch(item, payload),
                        ),
                    };
                }

                return {
                    ...window,
                    pendingOdds: [
                        ...window.pendingOdds,
                        {
                            bet: 0,
                            tournamentEventId: payload.tournamentEventId,
                            eventId: payload.eventId,
                            type: payload.type,
                        },
                    ],
                };
            });
        },

        updateOdd(state, payload: PendingOddPayload) {
            state._windows = state._windows.map(window => {
                if (window.id !== payload.windowId) {
                    return window;
                }

                return {
                    ...window,
                    pendingOdds: window.pendingOdds.map(item => {
                        if (!pendingOddsMatch(item, payload)) {
                            return item;
                        }

                        return {
                            ...item,
                            bet: payload.bet,
                        };
                    }),
                };
            });
        },

        removeOdds(state, payload: number) {
            state._windows = state._windows.map(window => {
                if (window.id !== payload) {
                    return window;
                }

                return {
                    ...window,
                    pendingOdds: [],
                };
            });
        },

        updateOddsBet(state, payload: UpdateOddsBetPayload) {
            state._windows = state._windows.map(window => {
                if (window.id !== payload.windowId) {
                    return window;
                }

                return {
                    ...window,
                    pendingOdds: window.pendingOdds.map(pendingOdd => ({
                        ...pendingOdd,
                        bet: payload.bet,
                    })),
                };
            });
        },
    },
};

export default module;
