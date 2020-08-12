import VueRouter, { RouteConfig } from "vue-router";
import HomeView from "../views/HomeView.vue";
import NotFoundView from "../views/NotFoundView.vue";
import TournamentView from "../views/TournamentView.vue";
import { scrollBehavior } from "./utils";
import HistoryView from "../views/HistoryView.vue";
import SignInForm from "../molecules/auth/SignInForm.vue";
import SignUpForm from "../molecules/auth/SignUpForm.vue";
import AboutView from "../views/AboutView.vue";
import SoonView from "../views/SoonView.vue";
import SupportView from "../views/SupportView.vue";

export const createRouter = (): VueRouter => {
    const routes: RouteConfig[] = [
        { path: "/", component: HomeView },
        { path: "/login", component: SignInForm },
        { path: "/signup", component: SignUpForm },
        { path: "/tournaments/:tournamentId", component: TournamentView },
        { path: "/history", component: HistoryView },
        { path: "/about", component: AboutView },
        { path: "/soon", component: SoonView },
        { path: "/support", component: SupportView },
        { path: "*", component: NotFoundView },
    ];

    return new VueRouter({
        mode: "history",
        routes,
        linkActiveClass: "active",
        scrollBehavior,
    });
};
