import VueRouter, { RouteConfig } from "vue-router";
import HomeView from "../views/HomeView.vue";
import NotFoundView from "../views/NotFoundView.vue";
import TournamentView from "../views/TournamentView.vue";
import { scrollBehavior } from "./utils";

export const createRouter = (): VueRouter => {
    const routes: RouteConfig[] = [
        { path: "/", component: HomeView },
        { path: "/tournaments/:tournamentId", component: TournamentView },
        { path: "*", component: NotFoundView },
    ];

    return new VueRouter({
        mode: "history",
        routes,
        linkActiveClass: "active",
        scrollBehavior,
    });
};
