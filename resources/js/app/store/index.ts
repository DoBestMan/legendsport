import Vuex, { Store } from "vuex";
import axios from "axios";
import sport from "./modules/sport";
import tournamentList from "./modules/tournamentList";
import tabs from "./modules/tabs";
import { Api } from "../api/Api";
import { RootState } from "./types";

export const createStore = (): Store<RootState> => {
    const axiosInstance = axios.create({
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
    });

    return new Vuex.Store({
        state: {
            api: new Api(axiosInstance),
        } as any,
        modules: {
            sport,
            tabs,
            tournamentList,
        },
        strict: process.env.NODE_ENV !== "production",
    });
};
