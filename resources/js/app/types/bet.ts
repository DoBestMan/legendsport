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
    scoreAway: number | null;
    scoreHome: number | null;
    selectedTeam: string | null;
    startsAt: string;
    status: BetStatus;
    teamAway: string;
    teamHome: string;
    type: PendingOddType;
    handicap: string;
}

export enum BetStatus {
    Win = "win",
    Loss = "loss",
    Push = "push",
    Pending = "pending",
}
