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
    id: number;
    externalId: string;
    odd: number;
    scoreAway: number;
    scoreHome: number;
    selectedTeam: string;
    startsAt: string;
    status: BetStatus;
    teamAway: string;
    teamHome: string;
    type: PendingOddType;
}

export enum BetStatus {
    Win = "win",
    Loss = "loss",
    Push = "push",
    Pending = "pending",
}
