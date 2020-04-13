export interface Game {
    id: number;
    externalId: string;
    startsAt: string;
    sportId: string;
    teamHome: string;
    teamAway: string;
    scoreHome: number;
    scoreAway: number;
}
