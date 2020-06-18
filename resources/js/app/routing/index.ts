import VueRouter, { RouteConfig } from "vue-router";
import HomeView from "../views/HomeView.vue";
import NotFoundView from "../views/NotFoundView.vue";
import TournamentView from "../views/TournamentView.vue";
import { scrollBehavior } from "./utils";
import HistoryView from "../views/HistoryView.vue";

export const createRouter = (): VueRouter => {
    const routes: RouteConfig[] = [
        { path: "/", component: HomeView },
        { path: "/tournaments/:tournamentId", component: TournamentView },
        { path: "/history", component: HistoryView},
        { path: "*", component: NotFoundView }
    ];

    return new VueRouter({
        mode: "history",
        routes,
        linkActiveClass: "active",
        scrollBehavior,
    });
};
