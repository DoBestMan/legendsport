import { Tournament } from "../types/tournament";
import { Player } from "../types/player";

export const mapTournament = (data: any): Tournament => ({
    id: data.id,
    balance: data.balance,
    buy_in: data.buy_in,
    enrolled: data.enrolled,
    name: data.name,
    players_limit: data.players_limit,
    sport_ids: [...new Set<number>(data.games.map((game: any) => game.sport_id))],
    starts: data.starts,
    state: data.state,
    time_frame: data.time_frame,
    games: data.games,
    players: data.players.map(mapPlayer),
});

export const mapPlayer = (data: any): Player => ({
    id: data.id,
    name: data.name,
    chips: data.chips,
});
