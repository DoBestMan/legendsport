import Vuex, { Store } from "vuex";
import axios from "axios";
import chat from "./modules/chat";
import odd from "./modules/odd";
import sport from "./modules/sport";
import loader from "./modules/loader";
import placeBet from "./modules/placeBet";
import user from "./modules/user";
import tournamentList from "./modules/tournamentList";
import windowModule from "./modules/window";
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
            chat,
            loader,
            odd,
            placeBet,
            sport,
            tournamentList,
            user,
            window: windowModule,
        },
        strict: process.env.NODE_ENV !== "production",
    });

    store.watch(state => state.window._windows, saveWindows);

    return store;
};
