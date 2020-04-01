export interface UserPlayer {
    id: number;
    chips: number;
    tournamentId: number;
}

export interface User {
    id: number;
    name: string;
    balance: number;
    players: UserPlayer[];
}
