import { TournamentState } from "../../general/types/tournament";
import { Game } from "./game";

export interface Tournament {
    id: number;
    buy_in: number;
    enrolled: number;
    name: string;
    players_limit: PlayersLimitType;
    sport_ids: number[];
    starts: string | null;
    state: TournamentState;
    time_frame: TimeFrame;
    games: Game[];
}

export enum BuyInType {
    Freeroll = "Freeroll",
    Low = "Low",
    Medium = "Medium",
    High = "High",
}

export enum TournamentType {
    Single = "Single",
    Multiple = "Multiple",
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
