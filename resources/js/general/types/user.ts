import { Bet } from "../../app/types/bet";

export interface UserPlayer {
    id: number;
    chips: number;
    tournamentId: number;
}

export interface User {
    id: number;
    name: string;
    balance: number;
    token: string;
    bets: Bet[];
    players: UserPlayer[];
}
