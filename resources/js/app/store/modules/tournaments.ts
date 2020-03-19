import { Module } from "vuex";
import { RootState } from "../types";
import { Tournament } from "../../types/tournament";

interface State {
    tournaments: Tournament[];
}

const module: Module<State, RootState> = {
    namespaced: true,
    state: {
        tournaments: [],
    },
    mutations: {
        addTournament(state, payload: Tournament) {
            const existing = state.tournaments.find(tournament => tournament.id === payload.id);
            if (!existing) {
                state.tournaments.push(payload);
            }
        },
        removeTournament(state, payload: number) {
            state.tournaments = state.tournaments.filter(tournament => tournament.id !== payload);
        },
    },
};

export default module;
