import { PendingOddType } from "./window";

export interface Bet {
    chipsWager: number;
    chipsWin: number;
    events: BetEvent[];
    id: number;
    status: BetStatus;
    tournamentId: number;
}

export interface BetEvent {
    awayTeam: string;
    homeTeam: string;
    id: number;
    matchTime: string;
    odd: number;
    selectedTeam: string;
    status: BetStatus;
    type: PendingOddType;
}

export enum BetStatus {
    Win = "win",
    Loss = "loss",
    Pending = "pending",
}
