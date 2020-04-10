import { Prize, Tournament } from "../types/tournament";
import { Player } from "../types/player";
import { User, UserPlayer } from "../../general/types/user";
import { Bet, BetEvent } from "../types/bet";
import { Odd } from "../types/odd";
import { Game } from "../types/game";

export const mapTournament = (data: any): Tournament => {
    const games: Game[] = data.games.map(mapGame);
    return {
        buyIn: data.buy_in,
        chips: data.chips,
        commission: data.commission,
        games: games,
        id: data.id,
        name: data.name,
        players: data.players.map(mapPlayer),
        playersLimit: data.players_limit,
        prizePoolMoney: data.prize_pool_money,
        sportIds: [...new Set(games.map(game => game.sport_id))],
        starts: data.starts,
        state: data.state,
        timeFrame: data.time_frame,
        prizePool: data.prize_pool.map(mapPrize),
    };
};

export const mapGame = (data: any): Game => ({
    id: data.id,
    event_id: data.external_id,
    starts_at: data.starts_at,
    sport_id: data.sport_id,
    home_team: data.home_team,
    away_team: data.away_team,
});

export const mapPlayer = (data: any): Player => ({
    id: data.id,
    name: data.name,
    chips: data.chips,
    balance: data.balance,
    userId: data.user_id,
});

export const mapPrize = (data: any): Prize => ({
    maxPosition: data.max_position,
    prize: data.prize,
});

export const mapMe = (data: any): User => ({
    id: data.id,
    name: data.name,
    balance: data.balance,
    token: data.token,
    bets: data.bets.map(mapBet),
    players: data.players.map(mapMePlayer),
});

export const mapMePlayer = (data: any): UserPlayer => ({
    id: data.id,
    chips: data.chips,
    pendingChips: data.pending_chips,
    tournamentId: data.tournament_id,
});

export const mapBet = (data: any): Bet => ({
    chipsWager: data.chips_wager,
    chipsWin: data.chips_win,
    events: data.events.map(mapBetEvent),
    id: data.id,
    status: data.status,
    tournamentId: data.tournament_id,
});

export const mapBetEvent = (data: any): BetEvent => ({
    awayTeam: data.away_team,
    homeTeam: data.home_team,
    id: data.id,
    startsAt: data.starts_at,
    odd: data.odd,
    selectedTeam: data.selected_team,
    status: data.status,
    type: data.type,
});

export const mapOdd = (data: any): Odd => ({
    ...data,
});
