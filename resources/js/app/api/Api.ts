import { AxiosInstance } from "axios";
import { mapTournament } from "./mappings";
import { Tournament } from "../types/tournament";
import { Sport } from "../../general/types/sport";
import { Odd } from "../../general/types/odd";

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
}
