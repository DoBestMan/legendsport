import { WindowState } from "./modules/window";
import { TournamentListState } from "./modules/tournamentList";
import { Api } from "../api/Api";
import { SportState } from "./modules/sport";
import { OddState } from "./modules/odd";

export interface RootState {
    api: Api;
    odd: OddState;
    sport: SportState;
    tournamentList: TournamentListState;
    window: WindowState;
}

export interface UpdateFieldPayload<T> {
    field: keyof T;
    value: T[keyof T];
}
