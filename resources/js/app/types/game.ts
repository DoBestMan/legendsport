export interface Game {
    id: number;
    externalId: string;
    startsAt: string;
    sportId: string;
    teamHome: string;
    teamAway: string;
    scoreHome: number;
    scoreAway: number;
    timeStatus: GameState;
}

export enum GameState {
    NotStarted = "Not Started",
    ToBeFixed = "To Be Fixed",
    InPlay = "In Play",
    Ended = "Ended",
    Canceled = "Canceled",
}
