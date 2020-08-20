import { Module } from "vuex";
import { RootState } from "../types";
import {
    BuyInType,
    PlayersLimitType,
    TimeFrame,
    Tournament,
    TournamentType,
} from "../../types/tournament";
import { updateField } from "../utils";
import { filterTournament } from "../../utils/tournament/filter";

export interface TournamentListState {
    tournaments: Tournament[];
    isLoading: boolean;
    isLoaded: boolean;
    isFailed: boolean;

    buyIn: BuyInType | null;
    playersLimit: PlayersLimitType | null;
    timeFrame: TimeFrame | null;
    search: string;
    sports: number[];
    type: TournamentType | null;
    upcoming: boolean;
}

const module: Module<TournamentListState, RootState> = {
    namespaced: true,

    state: {
        tournaments: [],
        isLoading: false,
        isLoaded: false,
        isFailed: false,

        buyIn: null,
        playersLimit: null,
        search: "",
        sports: [],
        timeFrame: null,
        type: null,
        upcoming: false,
    },

    getters: {
        filteredTournaments(state): Tournament[] {
            const search = state.search.toLowerCase();
            return state.tournaments.filter(tournament =>
                filterTournament(
                    tournament,
                    search,
                    state.sports,
                    state.buyIn,
                    state.type,
                    state.playersLimit,
                    state.upcoming,
                    state.timeFrame,
                ),
            );
        },
    },

    mutations: {
        updateField,

        markAsLoading(state) {
            state.isLoading = true;
        },

        markAsLoaded(state, tournaments: Tournament[]) {
            state.isLoading = false;
            state.isLoaded = true;
            state.isFailed = false;
            state.tournaments = tournaments;
        },

        markAsFailed(state) {
            state.isLoading = false;
            state.isFailed = true;
        },

        createOrUpdateTournament(state, tournament: Tournament) {
            const existingTournament = state.tournaments.find(item => item.id === tournament.id);

            if (existingTournament) {
                state.tournaments = state.tournaments.map(item =>
                    item.id === tournament.id ? tournament : item,
                );
            } else {
                state.tournaments = [...state.tournaments, tournament];
            }
        },

        clearSearch(state) {
            state.search = "";
        },
    },

    actions: {
        load({ state, dispatch }) {
            if (!state.tournaments.length) {
                dispatch("reload");
            }
        },

        async reload({ commit, rootState }) {
            commit("markAsLoading");

            try {
                const tournaments = await rootState.api.getTournaments();
                commit("markAsLoaded", tournaments);
            } catch (e) {
                commit("markAsFailed");
            }
        },
    },
};

export default module;
