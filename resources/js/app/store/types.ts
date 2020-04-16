import { WindowState } from "./modules/window";
import { TournamentListState } from "./modules/tournamentList";
import { Api } from "../api/Api";
import { SportState } from "./modules/sport";
import { OddState } from "./modules/odd";
import { UserState } from "./modules/user";
import { LoaderState } from "./modules/loader";
import { PlaceBetState } from "./modules/placeBet";
import { ChatState } from "./modules/chat";
import { AuthModalState } from "./modules/authModal";

export interface RootState {
    api: Api;
    authModal: AuthModalState;
    chat: ChatState;
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
