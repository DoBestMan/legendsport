import Vuex, { Store } from "vuex";
import tournaments from "./modules/tournaments";
import { RootState } from "./types";

export const createStore: () => Store<RootState> = () =>
    new Vuex.Store({
        modules: {
            tournaments,
        },
        strict: process.env.NODE_ENV !== "production",
    });
