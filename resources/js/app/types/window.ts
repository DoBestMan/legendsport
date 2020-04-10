import { Tournament } from "./tournament";

export interface Window extends Required<StorableWindow> {
    tournament: Tournament;
}

export interface StorableWindow {
    id: number;
    pendingOdds: PendingOdd[];
    selectedBetTypeTab: BetTypeTab;
    selectedSportIds: string[];
    parlayWager: number;
}

export enum BetTypeTab {
    Pending = "Pending",
    History = "History",
    Straight = "Straight",
    Parlay = "Parlay",
}

export interface PendingOdd {
    tournamentEventId: number;
    eventId: string;
    type: PendingOddType;
    wager?: number;
}

export enum PendingOddType {
    MoneyLineHome = "money_line_home",
    MoneyLineAway = "money_line_away",
    SpreadHome = "spread_home",
    SpreadAway = "spread_away",
    TotalUnder = "total_under",
    TotalOver = "total_over",
}
