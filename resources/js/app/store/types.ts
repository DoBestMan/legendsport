import { WindowState } from "./modules/window";
import { TournamentListState } from "./modules/tournamentList";
import { Api } from "../api/Api";
import { SportState } from "./modules/sport";
import { OddState } from "./modules/odd";
import { UserState } from "./modules/user";
import { LoaderState } from "./modules/loader";
import { PlaceBetState } from "./modules/placeBet";
import { BetState } from "./modules/bet";

export interface RootState {
    api: Api;
    bet: BetState;
    loader: LoaderState;
    odd: OddState;
    placeBet: PlaceBetState;
    sport: SportState;
    tournamentList: TournamentListState;
    user: UserState;
    window: WindowState;
}

export interface UpdateFieldPayload<T> {
    field: keyof T;
    value: T[keyof T];
}
