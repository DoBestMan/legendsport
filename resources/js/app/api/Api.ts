import { AxiosInstance } from "axios";
import { Tournament } from "../types/tournament";
import { Sport } from "../../general/types/sport";

export class Api {
    public constructor(private readonly axios: AxiosInstance) {
        //
    }

    public async getTournaments(): Promise<Tournament[]> {
        const response = await this.axios.get("/api/tournaments");
        return response.data;
    }

    public async getSports(): Promise<Sport[]> {
        const response = await this.axios.get("/api/sports");
        return response.data;
    }
}
