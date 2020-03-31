import { PendingOddType } from "./window";

export interface Bet {
    chips_wager: number;
    chips_win: number;
    events: BetEvent[];
    id: number;
    tournament_id: number;
}

export interface BetEvent {
    away_team: string;
    home_team: string;
    id: number;
    match_time: string;
    odd: number;
    selected_team: string;
    status: BetEventStatus;
    type: PendingOddType;
}

export enum BetEventStatus {
    Win = "win",
    Loss = "loss",
    Pending = "pending",
}
