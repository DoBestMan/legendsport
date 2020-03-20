import Vuex, { Store } from "vuex";
import tournamentList from "./modules/tournamentList";
import tabs from "./modules/tabs";
import { RootState } from "./types";

export const createStore: () => Store<RootState> = () =>
    new Vuex.Store({
        modules: {
            tabs,
            tournamentList,
        },
        strict: process.env.NODE_ENV !== "production",
    });
