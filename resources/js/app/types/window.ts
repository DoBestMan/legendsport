import { Tournament } from "./tournament";

export interface Window extends Required<StorableWindow> {
    tournament: Tournament;
}

export interface StorableWindow {
    id: number;
    pendingOdds: PendingOdd[];
    selectedBetTypeTab: BetTypeTab;
    selectedTournamentInfoTab: TournamentInfoTab;
    selectedSportIds: string[];
    parlayWager: number;
}

export enum BetTypeTab {
    Straight = "Straight",
    Parlay = "Parlay",
    Pending = "Pending",
    History = "History",
}

export enum TournamentInfoTab {
    Games = "GAMES",
    Ranks = "RANKS",
    Prizes = "PRIZES",
}

export interface PendingOdd {
    tournamentEventId: number;
    externalId: string;
    type: PendingOddType;
    wager?: number;
}

export enum PendingOddType {
    MoneyLineHome = "moneyline_home",
    MoneyLineAway = "moneyline_away",
    SpreadHome = "spread_home",
    SpreadAway = "spread_away",
    TotalUnder = "total_under",
    TotalOver = "total_over",
}
