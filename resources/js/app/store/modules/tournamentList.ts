import { Module } from "vuex";
import axios from "axios";
import { RootState } from "../types";
import {
    BuyInType,
    PlayersLimitType,
    TimeFrame,
    Tournament,
    TournamentType,
} from "../../types/tournament";
import { TournamentState } from "../../../general/types/tournament";
import { empty, intersects } from "../../../general/utils/utils";
import { updateField } from "../utils";

const matchString = (subject: string, needle?: string): boolean => {
    if (!needle) {
        return true;
    }

    if (!subject) {
        return false;
    }

    return subject.toLowerCase().includes(needle);
};

const matchBuyIn = (expected: BuyInType | null, value: number): boolean => {
    switch (expected) {
        case BuyInType.High:
            return 250 < value;
        case BuyInType.Medium:
            return 50 <= value && value <= 250;
        case BuyInType.Low:
            return 1 <= value && value < 50;
        case BuyInType.Freeroll:
            return value === 0;
        default:
            return true;
    }
};

const matchType = (expected: TournamentType | null, sports: number[]): boolean => {
    switch (expected) {
        case TournamentType.Single:
            return sports.length === 1;
        case TournamentType.Multiple:
            return sports.length > 1;
        default:
            return true;
    }
};

const matchPlayersLimit = (expected: PlayersLimitType | null, value: PlayersLimitType): boolean =>
    !expected || expected === value;

const matchTimeFrame = (expected: TimeFrame | null, value: TimeFrame | null): boolean =>
    !expected || expected === value;

const isUpcoming = (state: TournamentState): boolean =>
    [
        TournamentState.Announced,
        TournamentState.LateRegistering,
        TournamentState.Registering,
    ].includes(state);

const filterTournament = (
    tournament: Tournament,
    search: string,
    sports: number[],
    buyIn: BuyInType | null,
    type: TournamentType | null,
    playersLimit: PlayersLimitType | null,
    upcoming: boolean,
    timeFrame: TimeFrame | null,
): boolean => {
    if (search) {
        return matchString(tournament.name, search);
    }

    return (
        matchBuyIn(buyIn, tournament.buy_in) &&
        matchType(type, tournament.sport_ids) &&
        matchPlayersLimit(playersLimit, tournament.players_limit) &&
        (empty(sports) || intersects(sports, tournament.sport_ids)) &&
        (!upcoming || isUpcoming(tournament.state)) &&
        matchTimeFrame(timeFrame, tournament.time_frame)
    );
};

export interface TournamentListState {
    tournaments: Tournament[];
    isLoading: boolean;
    hasFailed: boolean;

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
        hasFailed: false,

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
            state.hasFailed = false;
            state.tournaments = tournaments;
        },

        markAsFailed(state) {
            state.isLoading = false;
            state.hasFailed = true;
        },
    },

    actions: {
        load({ state, dispatch }) {
            if (!state.tournaments.length) {
                dispatch("reload");
            }
        },

        async reload({ commit }) {
            commit("markAsLoading");

            try {
                const response = await axios.get("/api/tournaments");
                commit("markAsLoaded", response.data);
            } catch (e) {
                commit("markAsFailed");
            }
        },
    },
};

export default module;
