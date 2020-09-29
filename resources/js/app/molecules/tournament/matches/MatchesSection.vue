<template>
    <section class="layout__content__container layout__content__container__game">
        <div class="layout__content__container__mobile" v-show="!isModalBetSlipSection">
            <div class="layout__content__container__mobile__switch">
                <div class="layout__content__container__mobile__switch__icon" @click="goToHome">
                    <i class="icon icon--left icon--color--light-1"></i>
                </div>
                <div class="layout__content__container__mobile__switch__icon">
                    <i class="icon icon--all icon--micro"></i>
                </div>
                <div class="layout__content__container__mobile__switch__title">
                    All Sports
                </div>
                <div
                    class="layout__content__container__mobile__switch__icon"
                    @click="handleModalTournamentSwitch"
                >
                    <i class="icon icon--down icon--micro icon--color--light-1"></i>
                </div>
            </div>

            <div class="layout__content__sidebar__header__bet__content">
                <div class="layout__content__sidebar__header__bet__content__group">
                    <div class="layout__content__sidebar__header__bet__content__group__coins">
                        <i class="icon icon--atom icon--color--yellow-2 icon--coins m--r--1"></i>
                        Balance
                    </div>
                    <div class="layout__content__sidebar__header__bet__content__group__balance">
                        {{ player ? player.chips : 0 | formatChip }} ({{
                            player ? player.pendingChips : 0 | formatChip
                        }})
                    </div>
                </div>
            </div>

            <div class="layout__content__container__mobile__icons">
                <i class="icon icon--search icon--color--light-1 m--l--4"></i>
                <i class="icon icon--refresh icon--color--light-1 m--l--4"></i>
            </div>
        </div>

        <div class="layout__content__container__content layout__content__container__game__content">
            <div class="odds">
                <div class="tab--large d--only--desktop">
                    <div
                        class="tab--large__item"
                        :class="{ 'tab--large__item--active': areAllSportsSelected }"
                        @click="selectAllSports"
                    >
                        All
                    </div>

                    <div
                        v-for="sportId in tournament.sportIds"
                        :key="sportId"
                        class="tab--large__item"
                        :class="{ 'tab--large__item--active': isSportSelected(sportId) }"
                        @click="toggleSport(sportId)"
                    >
                        {{ getSportName(sportId) }}
                    </div>
                </div>

                <div
                    class="modal modal--active"
                    v-show="isModalTournamentSwitch && !isModalBetSlipSection"
                >
                    <div class="modal__row" @click="selectAllSports">
                        <div class="modal__row__item">
                            <i
                                class="icon icon--small icon--all m--r--2"
                                :class="{ 'icon--color--yellow-2': areAllSportsSelected }"
                            ></i>
                            All Sports
                        </div>
                        <div class="modal__row__item">
                            <i
                                class="icon icon--small icon--check"
                                :class="{ 'icon--color--yellow-2': areAllSportsSelected }"
                            ></i>
                        </div>
                    </div>

                    <div
                        v-for="sportId in tournament.sportIds"
                        :key="sportId"
                        class="modal__row"
                        @click="toggleSport(sportId)"
                    >
                        <div class="modal__row__item">
                            <i :class="classObject(sportId)"></i>
                            {{ getSportName(sportId) }}
                        </div>
                        <div class="modal__row__item">
                            <i
                                class="icon icon--small icon--check"
                                :class="{ 'icon--color--yellow-2': isSportSelected(sportId) }"
                            ></i>
                        </div>
                    </div>
                </div>

                <!-- <div class="odds__header" v-show="!isModalBetSlipSection">
                    <div class="odds__header__tabs">
                        <div class="odds__header__tabs__tab odds__header__tabs__tab--active">
                            GAME LINE
                        </div>
                        <div class="odds__header__tabs__tab">
                            1st HALF
                        </div>
                        <div class="odds__header__tabs__tab">
                            2nd HALF
                        </div>
                    </div>
                    <div class="odds__header__button">
                        REFRESH ODDS
                    </div>
                </div> -->

                <div class="odds__scroll" v-show="!isModalBetSlipSection">
                    <div class="odd" v-for="(games, date) in groupedGames" :key="date">
                        <div class="odd__header">
                            <div class="odd__header__time">{{ date | toDateTime }}</div>
                            <div class="odd__header__table">
                                <div class="odd__header__table__item">
                                    MONEY
                                </div>
                                <div class="odd__header__table__item">
                                    SPREAD
                                </div>
                                <div class="odd__header__table__item">
                                    TOTAL
                                </div>
                            </div>
                            <div class="odd__header__margin"></div>
                        </div>
                        <div class="odd__container">
                            <GameRow
                                :key="game.id"
                                :window="window"
                                :game="game"
                                @toggleOdd="toggleOdd"
                                v-for="game in games"
                            />
                            <div class="odd__container__seperator"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="layout__content__sidebar__chat layout__content__slip__mobile"
                @click="goToBetSlip"
            >
                <div class="layout__content__sidebar__chat__cta">
                    <div class="layout__content__sidebar__chat__cta__title">
                        <i class="icon icon--micro icon--slip icon--color--light-1 m--r--2"></i>
                        BET SLIP
                    </div>
                    <div class="layout__content__sidebar__chat__cta__action">
                        <span class="tag tag--color--red tag--medium m--r--2">{{
                            pendingOdds.length
                        }}</span>
                    </div>
                </div>
            </div>
        </div>

        <BetsMobileSection
            v-if="isModalBetSlipSection"
            :window="window"
            @backMatchSection="backMatchSection"
        />
    </section>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { PendingOdd, Window } from "../../../types/window";
import { Tournament } from "../../../types/tournament";
import GameRow from "./GameRow.vue";
import { Game, GameState } from "../../../types/game";
import { empty, groupBy } from "../../../../general/utils/utils";
import BetsMobileSection from "../bets/BetsMobileSection.vue";
import { UserPlayer } from "../../../../general/types/user";
import {
    PendingOddPayload,
    ToggleSportPayload,
    UpdateWindowPayload,
} from "../../../store/modules/window";

export default Vue.extend({
    name: "MatchesSection",
    components: { GameRow, BetsMobileSection },

    props: {
        window: Object as PropType<Window>,
    },

    data() {
        return {
            isModalTournamentSwitch: false,
            isModalBetSlipSection: false,
        };
    },

    created() {
        window.addEventListener("resize", this.isMobile);
    },

    destroyed() {
        window.removeEventListener("resize", this.isMobile);
    },

    computed: {
        tournament(): Tournament {
            return this.window.tournament;
        },

        areAllSportsSelected(): boolean {
            return this.window.selectedSportIds.length === 0;
        },

        groupedGames(): Record<string, Game[]> {
            let filteredGames = this.tournament.games.filter(
                game =>
                    (empty(this.window.selectedSportIds) ||
                        this.window.selectedSportIds.includes(game.sportId)) &&
                    game.timeStatus === GameState.NotStarted,
            );
            return groupBy(filteredGames, game => game.startsAt);
        },

        gameDict(): ReadonlyMap<string, Game> {
            return new Map(this.window.tournament.games.map(game => [game.externalId, game]));
        },

        pendingOdds(): PendingOdd[] {
            return this.window.pendingOdds.filter(pendingOdd =>
                this.gameDict.has(pendingOdd.externalId),
            );
        },

        player(): UserPlayer | null {
            const playersDict: ReadonlyMap<number, UserPlayer> = this.$stock.getters[
                "user/playersDictByTournament"
            ];
            return playersDict.get(this.tournament.id) ?? null;
        },
    },

    methods: {
        classObject(sportId: string) {
            let className = "icon icon--small m--r--2 ";
            if (this.isSportSelected(sportId)) className += "icon--color--yellow-2 ";
            const sportsNames = ["MLB", "NFL", "NCAAF", "NBA", "NHL"];
            const iconNames = [
                "icon--sport-baseball",
                "icon--sport-nfl",
                "icon--sport-nfl",
                "icon--sport-nba",
                "icon--sport-hockey",
            ];
            const index = sportsNames.indexOf(this.getSportName(sportId));
            return className + iconNames[index];
        },

        isMobile(): void {
            if (window.innerWidth > 992) {
                this.isModalBetSlipSection = false;
            }
        },

        getSportName(sportId: string): string {
            const dict: ReadonlyMap<string, string> = this.$stock.getters["sport/sportDictionary"];
            return dict.get(sportId) ?? String(sportId);
        },

        isSportSelected(sportId: string): boolean {
            return this.window.selectedSportIds.includes(sportId);
        },

        selectAllSports(): void {
            const payload: UpdateWindowPayload = {
                id: this.window.id,
                selectedSportIds: [],
            };
            this.$stock.commit("window/updateWindow", payload);
        },

        toggleSport(sportId: string): void {
            const payload: ToggleSportPayload = {
                windowId: this.window.id,
                sportId,
            };
            this.$stock.commit("window/toggleSport", payload);
        },

        toggleOdd(pendingOdd: PendingOdd): void {
            const payload: PendingOddPayload = {
                windowId: this.window.id,
                tournamentEventId: pendingOdd.tournamentEventId,
                externalId: pendingOdd.externalId,
                type: pendingOdd.type,
            };
            this.$stock.dispatch("window/toggleOdd", payload);
        },

        handleModalTournamentSwitch(): void {
            this.isModalTournamentSwitch = !this.isModalTournamentSwitch;
        },

        goToHome(): void {
            this.$router.push("/");
        },

        goToBetSlip(): void {
            this.isModalBetSlipSection = true;
        },

        backMatchSection(): void {
            this.isModalBetSlipSection = false;
        },
    },
});
</script>
