import Vuex, { Store } from "vuex";
import axios from "axios";
import odd from "./modules/odd";
import sport from "./modules/sport";
import user from "./modules/user";
import tournamentList from "./modules/tournamentList";
import window from "./modules/window";
import { Api } from "../api/Api";
import { RootState } from "./types";
import { saveWindows } from "../utils/local-storage/LocalStorageManager";

export const createStore = (): Store<RootState> => {
    const axiosInstance = axios.create({
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
    });

    const store = new Vuex.Store({
        state: {
            api: new Api(axiosInstance),
        } as any,
        modules: {
            odd,
            sport,
            user,
            window,
            tournamentList,
        },
        strict: process.env.NODE_ENV !== "production",
    });

    store.watch(state => state.window._windows, saveWindows);

    return store;
};
