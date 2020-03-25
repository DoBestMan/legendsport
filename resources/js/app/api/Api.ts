import { AxiosInstance } from "axios";
import { mapTournament } from "./mappings";
import { Tournament } from "../types/tournament";
import { Sport } from "../../general/types/sport";
import { Odd } from "../../general/types/odd";
import { User } from "../../general/types/user";

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
        return response.data;
    }

    public async getMe(): Promise<User> {
        const response = await this.axios.get("/api/me");
        return response.data;
    }

    public async logout(): Promise<void> {
        await this.axios.post("/api/logout");
    }
}
