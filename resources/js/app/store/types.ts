import { WindowState } from "./modules/window";
import { TournamentListState } from "./modules/tournamentList";
import { Api } from "../api/Api";
import { SportState } from "./modules/sport";
import { OddState } from "./modules/odd";
import { UserState } from "./modules/user";

export interface RootState {
    api: Api;
    odd: OddState;
    sport: SportState;
    tournamentList: TournamentListState;
    user: UserState;
    window: WindowState;
}

export interface UpdateFieldPayload<T> {
    field: keyof T;
    value: T[keyof T];
}
