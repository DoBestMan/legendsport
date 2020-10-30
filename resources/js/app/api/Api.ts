import { AxiosInstance } from "axios";
import { mapMe, mapOdd, mapTournament } from "./mappings";
import { Tournament } from "../types/tournament";
import { Sport } from "../../general/types/sport";
import { User } from "../../general/types/user";
import { PendingOddType } from "../types/window";
import { Odd } from "../types/odd";

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

export interface SignInBody {
    email: string;
    password: string;
}

export interface SignUpBody {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    firstname: string;
    lastname: string;
    dob: string;
}

export interface ChangePasswordBody {
    current_password: string;
    password: string;
    password_confirmation: string;
}

export interface ChangeEmailBody {
    current_password: string;
    email: string;
}

export interface ChangeProfileBody {
    name: string;
    firstname: string;
    lastname: string;
}

export interface WithdrawBody {
    amount: number | null;
    btcAddress: string;
}

export class Api {
    public constructor(private readonly axios: AxiosInstance) {
        //
    }

    public async getTournamentHistory(): Promise<Tournament[]> {
        const response = await this.axios.get("/api/history");
        return response.data.map(mapTournament);
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

    public async signIn(body: SignInBody): Promise<void> {
        await this.axios.post("/api/signin", body);
    }

    public async signUp(body: SignUpBody): Promise<void> {
        await this.axios.post("/api/signup", body);
    }

    public async changePassword(body: ChangePasswordBody): Promise<void> {
        await this.axios.post("/api/me/change-password", body);
    }

    public async changeEmail(body: ChangeEmailBody): Promise<void> {
        await this.axios.post("/api/me/change-email", body);
    }

    public async changeProfile(body: ChangeProfileBody): Promise<void> {
        await this.axios.post("/api/me/change-profile", body);
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

    public async withdraw(body: WithdrawBody): Promise<void> {
        await this.axios.post("/api/withdraw/btc", body);
    }
}
