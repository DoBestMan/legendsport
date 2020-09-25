<template>
    <div>
        <div class="game" v-for="game in games" :key="game.id">
            <div class="game__header">
                <div class="game__header__detail">
                    <div class="game__header__detail__date">{{ getStartsDateAt(game) }}</div>
                    <div class="game__header__detail__timezone">at {{ getStartsTimeAt(game) }}</div>
                </div>
                <div class="game__header__sport">
                    <i :class="classObject(getSportName(game))"></i>
                    {{ getSportName(game) }}
                </div>
            </div>
            <div class="game__footer">
                <div class="game__footer__detail">
                    <div class="game__footer__detail__label">AWAY</div>
                    <div class="game__footer__detail__team">{{ game.teamAway }}</div>
                </div>
                <div class="game__footer__score">
                    <div class="game__footer__score__time" v-if="game.timeStatus === 'In Play'" />
                    <div class="game__footer__score__time" v-if="game.timeStatus === 'Ended'">
                        F
                    </div>
                    <div class="game__footer__score__container">
                        <div class="game__footer__score__item">{{ game.scoreAway | score }}</div>
                        <div class="game__footer__score__seperator">:</div>
                        <div class="game__footer__score__item">{{ game.scoreHome | score }}</div>
                    </div>
                </div>
                <div class="game__footer__detail">
                    <div class="game__footer__detail__label game__footer__detail__label--right">
                        Home
                    </div>
                    <div class="game__footer__detail__team game__footer__detail__team--right">
                        {{ game.teamHome }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import moment from "moment";
import { Game } from "../../types/game";

export default Vue.extend({
    name: "TournamentGamesTable",

    props: {
        games: Array as PropType<Game[]>,
    },

    methods: {
        classObject(sportName: string) {
            let className = "m--r--1 icon icon--micro icon--color--light-2 ";
            const sportsNames = ["MLB", "NFL", "NCAAF", "NBA", "NHL"];
            const iconNames = [
                "icon--sport-baseball",
                "icon--sport-nfl",
                "icon--sport-nfl",
                "icon--sport-nba",
                "icon--sport-hockey",
            ];
            const index = sportsNames.indexOf(sportName);
            return className + iconNames[index];
        },

        getStartsDateAt(game: Game): string {
            return moment(game.startsAt).format("MMM, DD");
        },

        getStartsTimeAt(game: Game): string {
            return moment(game.startsAt).format("hh:mm zz");
        },

        getSportName(game: Game): string {
            const dict: ReadonlyMap<string, string> = this.$stock.getters["sport/sportDictionary"];
            return dict.get(game.sportId) ?? String(game.sportId);
        },
    },
});
</script>
