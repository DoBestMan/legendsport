import { Tournament } from "../types/tournament";
import { Player } from "../types/player";
import { User, UserPlayer } from "../../general/types/user";

export const mapTournament = (data: any): Tournament => ({
    buyIn: data.buy_in,
    chips: data.chips,
    games: data.games,
    id: data.id,
    name: data.name,
    players: data.players.map(mapPlayer),
    playersLimit: data.players_limit,
    sportIds: [...new Set<number>(data.games.map((game: any) => game.sport_id))],
    starts: data.starts,
    state: data.state,
    timeFrame: data.time_frame,
    userBalance: data.user_balance,
});

export const mapPlayer = (data: any): Player => ({
    id: data.id,
    name: data.name,
    chips: data.chips,
});

export const mapMe = (data: any): User => ({
    id: data.id,
    name: data.name,
    balance: data.balance,
    players: data.players.map(mapMePlayer),
});

export const mapMePlayer = (data: any): UserPlayer => ({
    id: data.id,
    chips: data.chips,
    tournamentId: data.tournament_id,
});
