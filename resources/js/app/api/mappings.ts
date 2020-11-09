import { Prize, Tournament } from "../types/tournament";
import { Player } from "../types/player";
import { User, UserPlayer, TournamentPlayer } from "../../general/types/user";
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
        sportIds: [...new Set(games.map(game => game.sportId))],
        starts: data.starts,
        state: data.state,
        timeFrame: data.time_frame,
        prizePool: data.prize_pool.map(mapPrize),
        liveLines: data.live_lines,
    };
};

export const mapGame = (data: any): Game => ({
    id: data.id,
    externalId: data.external_id,
    startsAt: data.starts_at,
    sportId: data.sport_id,
    teamHome: data.team_home,
    teamAway: data.team_away,
    pitcherHome: data.home_pitcher,
    pitcherAway: data.away_pitcher,
    scoreHome: data.score_home,
    scoreAway: data.score_away,
    timeStatus: data.time_status,
});

export const mapPlayer = (data: any): Player => ({
    id: data.id,
    name: data.name,
    chips: data.chips,
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

export const mapTournamentPlayer = (data: any): TournamentPlayer => ({
    id: data.id,
    name: data.name,
    tournamentId: data.tournamentId,
    bets: data.bets.map(mapBet),
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
    id: data.id,
    externalId: data.external_id,
    odd: data.odd,
    scoreAway: data.score_away,
    scoreHome: data.score_home,
    selectedTeam: data.selected_team,
    startsAt: data.starts_at,
    status: data.status,
    teamAway: data.team_away,
    teamHome: data.team_home,
    type: data.type,
    handicap: data.handicap,
});

export const mapOdd = (data: any): Odd => ({
    ...data,
});
