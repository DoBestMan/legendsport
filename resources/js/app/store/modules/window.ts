import { Module } from "vuex";
import { RootState } from "../types";
import { getWindows } from "../../utils/local-storage/LocalStorageManager";
import {
    BetTypeTab,
    TournamentInfoTab,
    PendingOdd,
    StorableWindow,
    Window,
} from "../../types/window";
import { DeepReadonlyArray, DeepReadonly } from "../../../general/types/types";

export interface WindowState {
    _windows: StorableWindow[];
    _activeWindowId: number;
}

export type UpdateWindowPayload = Partial<StorableWindow> & Pick<StorableWindow, "id">;

export interface ToggleSportPayload {
    windowId: number;
    sportId: string;
}

export interface PendingOddPayload extends PendingOdd {
    windowId: number;
}

export interface UpdateOddsWagerPayload {
    windowId: number;
    wager: number;
}

const pendingOddsMatch = (a: PendingOdd, b: PendingOdd): boolean =>
    a.externalId === b.externalId && a.type === b.type;

// TODO Remove windows of non-existing tournaments

const module: Module<WindowState, RootState> = {
    namespaced: true,

    state: {
        _windows: getWindows(),
        _activeWindowId: -1,
    },

    getters: {
        windows(state, _getters, rootState): DeepReadonlyArray<Window> {
            const tournamentDict = new Map(
                rootState.tournamentList.tournaments.map(tournament => [tournament.id, tournament]),
            );

            rootState.tournamentHistoryList.tournaments.forEach(function(tournament) {
                tournamentDict.set(tournament.id, tournament);
            });

            return state._windows
                .filter(window => tournamentDict.has(window.id))
                .map(tab => {
                    const tournament = tournamentDict.get(tab.id)!;

                    return {
                        pendingOdds: [],
                        selectedTournamentInfoTab: TournamentInfoTab.Games,
                        selectedBetTypeTab: BetTypeTab.Pending,
                        selectedSportIds: [],
                        ...tab,
                        tournament,
                    };
                });
        },

        activeWindowId(state, _getters): DeepReadonly<number> {
            return state._activeWindowId;
        },
    },

    mutations: {
        openWindow(state, payload: number) {
            state._activeWindowId = payload;
            const tabExists = !!state._windows.find(window => window.id === payload);
            if (tabExists) {
                return;
            }

            state._windows.push({
                id: payload,
                parlayWager: 0,
                pendingOdds: [],
                selectedTournamentInfoTab: TournamentInfoTab.Games,
                selectedBetTypeTab: BetTypeTab.Pending,
                selectedSportIds: [],
            });
        },

        closeWindow(state, payload: number) {
            state._activeWindowId = -1;
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

        toggleWindow(state, payload: number) {
            state._activeWindowId = payload;
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

                const newPendingOdd: PendingOdd = {
                    wager: 0,
                    tournamentEventId: payload.tournamentEventId,
                    externalId: payload.externalId,
                    type: payload.type,
                };

                return {
                    ...window,
                    pendingOdds: [...window.pendingOdds, newPendingOdd],
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
                            wager: payload.wager,
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

        updateOddsWager(state, payload: UpdateOddsWagerPayload) {
            state._windows = state._windows.map(window => {
                if (window.id !== payload.windowId) {
                    return window;
                }

                return {
                    ...window,
                    pendingOdds: window.pendingOdds.map(pendingOdd => ({
                        ...pendingOdd,
                        wager: payload.wager,
                    })),
                };
            });
        },
    },

    actions: {
        toggleOdd({ commit, state }, payload: PendingOddPayload) {
            commit("toggleOdd", payload);

            const window = state._windows.find(window => window.id === payload.windowId);

            if (!window) {
                return;
            }

            if ([BetTypeTab.Straight, BetTypeTab.Parlay].includes(window.selectedBetTypeTab)) {
                return;
            }

            const changeTabPayload: UpdateWindowPayload = {
                id: payload.windowId,
                selectedBetTypeTab: BetTypeTab.Straight,
            };
            commit("updateWindow", changeTabPayload);
        },
    },
};

export default module;
