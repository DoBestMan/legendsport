import { TournamentState } from "../../general/types/tournament";
import { Game } from "./game";
import { Player } from "./player";

export interface Tournament {
    id: number;
    userBalance: number | null;
    buyIn: number;
    chips: number;
    name: string;
    playersLimit: PlayersLimitType;
    sportIds: number[];
    starts: string | null;
    state: TournamentState;
    timeFrame: TimeFrame;
    games: Game[];
    players: Player[];
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
