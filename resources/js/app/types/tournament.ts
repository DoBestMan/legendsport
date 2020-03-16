import { TournamentState } from "../../general/types/tournament";

export interface Tournament {
    buy_in: number;
    enrolled: number;
    name: string;
    players_limit: PlayersLimitType;
    sport_id: number[];
    starts: string | null;
    state: TournamentState;
    time_frame: TimeFrame;
}

export enum BuyInType {
    Freeroll = 1,
    Low,
    Medium,
    High,
}

export enum TournamentType {
    Single = 1,
    Multiple,
}

export enum PlayersLimitType {
    HeadsUp = "Heads-Up",
    SingleTable = "Single Table",
    Unlimited = "Unlimited",
}

export enum TimeFrame {
    Daily = "Daily",
    Weekly = "Weekly",
    Monthly = "Monthly",
    SeasonLong = "Season long",
}
