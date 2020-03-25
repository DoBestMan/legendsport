import { Tournament } from "./tournament";

export interface Window extends Required<StorableWindow> {
    tournament: Tournament;
}

export interface StorableWindow {
    id: number;
    pendingOdds: PendingOdd[];
    selectedBetTypeTab: BetTypeTab;
    selectedSportIds: number[];
}

export enum BetTypeTab {
    Pending = "Pending",
    History = "History",
    Straight = "Straight",
    Parlay = "Parlay",
}

export interface PendingOdd {
    eventId: string;
    type: PendingOddType;
    bet?: number;
}

export enum PendingOddType {
    MoneyLineHome = 1,
    MoneyLineAway,
    SpreadHome,
    SpreadAway,
    TotalUnder,
    TotalOver,
}
