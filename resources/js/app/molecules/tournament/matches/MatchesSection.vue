<template>
    <section class="layout__content__container">
        <div class="layout__content__container__mobile">
            <div class="layout__content__container__mobile__switch">
                <div class="layout__content__container__mobile__switch__icon">
                    <i class="icon icon--left icon--color--light-1"></i>
                </div>
                <div class="layout__content__container__mobile__switch__icon" @click="goToHome">
                    <i class="icon icon--all icon--micro"></i>
                </div>
                <div class="layout__content__container__mobile__switch__title">
                    All Sports
                </div>
                <div class="layout__content__container__mobile__switch__icon">
                    <i class="icon icon--down icon--micro icon--color--light-1"></i>
                </div>
            </div>
            <div class="layout__content__container__mobile__icons">
                <i class="icon icon--search icon--color--light-1 m--l--4"></i>
                <i class="icon icon--refresh icon--color--light-1 m--l--4"></i>
            </div>
        </div>

        <div class="layout__content__container__content">
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

                <div class="odds__header">
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
                </div>

                <div class="odds__scroll">
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
        </div>
    </section>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import { PendingOdd, Window } from "../../../types/window";
import { Tournament } from "../../../types/tournament";
import GameRow from "./GameRow.vue";
import { Game, GameState } from "../../../types/game";
import { empty, groupBy } from "../../../../general/utils/utils";
import {
    PendingOddPayload,
    ToggleSportPayload,
    UpdateWindowPayload,
} from "../../../store/modules/window";

export default Vue.extend({
    name: "MatchesSection",
    components: { GameRow },

    props: {
        window: Object as PropType<Window>,
    },

    computed: {
        tournament(): Tournament {
            return this.window.tournament;
        },

        areAllSportsSelected(): boolean {
            return this.window.selectedSportIds.length === 0;
        },

        groupedGames(): Record<string, Game[]> {
            const filteredGames = this.tournament.games.filter(
                game =>
                    (empty(this.window.selectedSportIds) ||
                        this.window.selectedSportIds.includes(game.sportId)) &&
                    game.timeStatus === GameState.NotStarted,
            );
            return groupBy(filteredGames, game => game.startsAt);
        },
    },

    methods: {
        goToHome(): void {
            this.$router.push("/");
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
    },
});
</script>
