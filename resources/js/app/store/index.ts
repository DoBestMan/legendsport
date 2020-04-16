import Vuex, { Store } from "vuex";
import axios from "axios";
import authModal from "./modules/authModal";
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
            authModal,
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
};
