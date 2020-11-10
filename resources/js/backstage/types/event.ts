export interface Event {
    external_id: string;
    status: string;
    sport_id: string;
    starts_at: string;
    team_away: string;
    team_home: string;
    pitcher_away: string;
    pitcher_home: string;
    bets_placed: number;
    bets_graded: number;
    bot_bets_placed: number;
    bot_bets_graded: number;
}
