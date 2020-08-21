import VueRouter, { RouteConfig } from "vue-router";
import HomeView from "../views/HomeView.vue";
import NotFoundView from "../views/NotFoundView.vue";
import TournamentView from "../views/TournamentView.vue";
import { scrollBehavior } from "./utils";
import WindowBar from "../molecules/layout/WindowBar.vue";
import NavBar from "../molecules/layout/NavBar.vue";
import HistoryView from "../views/HistoryView.vue";
import SignInForm from "../molecules/auth/SignInForm.vue";
import SignUpForm from "../molecules/auth/SignUpForm.vue";
import AboutView from "../views/AboutView.vue";
import SoonView from "../views/SoonView.vue";
import SupportView from "../views/SupportView.vue";
import CashierView from "../molecules/cashier/CashierView.vue";
import ProfileView from "../molecules/auth/ProfileView.vue";
import ProfileEdit from "../molecules/auth/ProfileEdit.vue";
import BankWireMobileForm from "../molecules/cashier/BankWireMobileForm.vue";
import BitcoinMobileForm from "../molecules/cashier/BitcoinMobileForm.vue";
import CreditCardMobileForm from "../molecules/cashier/CreditCardMobileForm.vue";
import PaypalMobileForm from "../molecules/cashier/PaypalMobileForm.vue";
import MobileInfoSection from "../molecules/tournament/info/MobileInfoSection.vue";

export const createRouter = (): VueRouter => {
    const routes: RouteConfig[] = [
        { path: "/", component: HomeView, meta: { layout: [NavBar, WindowBar] } },
        { path: "/login", component: SignInForm },
        { path: "/signup", component: SignUpForm },
        { path: "/profile", component: ProfileView, meta: { layout: [NavBar] } },
        { path: "/profile-edit", component: ProfileEdit, meta: { layout: [NavBar] } },
        {
            path: "/tournaments/:tournamentId",
            component: TournamentView,
            meta: { layout: [NavBar, WindowBar] },
        },
        { path: "/history", component: HistoryView, meta: { layout: [NavBar] } },
        { path: "/about", component: AboutView, meta: { layout: [NavBar] } },
        { path: "/soon", component: SoonView },
        { path: "/support", component: SupportView, meta: { layout: [NavBar] } },
        { path: "/cashier", component: CashierView, meta: { layout: [NavBar] } },
        { path: "/bankwire", component: BankWireMobileForm, meta: { layout: [NavBar] } },
        { path: "/bitcoin", component: BitcoinMobileForm, meta: { layout: [NavBar] } },
        { path: "/cc", component: CreditCardMobileForm, meta: { layout: [NavBar] } },
        { path: "/paypal", component: PaypalMobileForm, meta: { layout: [NavBar] } },
        {
            path: "/tour_info/:tournamentId",
            component: MobileInfoSection,
            meta: { layout: [NavBar] },
        },
        { path: "*", component: NotFoundView, meta: { layout: [NavBar] } },
    ];

    return new VueRouter({
        mode: "history",
        routes,
        linkActiveClass: "active",
        scrollBehavior,
    });
};
