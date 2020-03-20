import { TabsState } from "./modules/tabs";
import { TournamentListState } from "./modules/tournamentList";

export interface RootState {
    tournamentList: TournamentListState;
    tabs: TabsState;
}

export interface UpdateFieldPayload<T> {
    field: keyof T;
    value: T[keyof T];
}
