import { AxiosInstance } from "axios";
import { mapMe, mapOdd, mapTournament } from "./mappings";
import { Tournament } from "../types/tournament";
import { Sport } from "../../general/types/sport";
import { Odd } from "../../general/types/odd";
import { User } from "../../general/types/user";
import { PendingOddType } from "../types/window";

export interface PlaceStraightBetBody {
    pending_odds: Array<{
        event_id: number;
        type: PendingOddType;
        wager: number;
    }>;
}

export interface PlaceParlayBetBody {
    pending_odds: Array<{
        event_id: number;
        type: PendingOddType;
    }>;
    wager: number;
}

export class Api {
    public constructor(private readonly axios: AxiosInstance) {
        //
    }

    public async getTournaments(): Promise<Tournament[]> {
        const response = await this.axios.get("/api/tournaments");
        return response.data.map(mapTournament);
    }

    public async getSports(): Promise<Sport[]> {
        const response = await this.axios.get("/api/sports");
        return response.data;
    }

    public async getOdds(): Promise<Odd[]> {
        const response = await this.axios.get("/api/odds");
        return response.data.map(mapOdd);
    }

    public async getMe(): Promise<User> {
        const response = await this.axios.get("/api/me");
        return mapMe(response.data);
    }

    public async logout(): Promise<void> {
        await this.axios.post("/api/logout");
    }

    public async placeStraightBet(tournamentId: number, body: PlaceStraightBetBody): Promise<void> {
        await this.axios.post(`/api/tournaments/${tournamentId}/bets/straight`, body);
    }

    public async placeParlayBet(tournamentId: number, body: PlaceParlayBetBody): Promise<void> {
        await this.axios.post(`/api/tournaments/${tournamentId}/bets/parlay`, body);
    }

    public async registerForTournament(tournamentId: number): Promise<void> {
        await this.axios.post(`/api/tournaments/${tournamentId}/register`);
    }
}
