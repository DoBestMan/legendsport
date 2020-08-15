<template>
    <div class="layout__content__container__filter">
        <div
            class="layout__content__container__filter__content layout__content__container__filter__content--sidebar"
        >
            <div class="label">
                SEARCH
            </div>
            <input id="search" class="input" placeholder="Search..." v-model="search" />
        </div>

        <div class="layout__content__container__filter__filters">
            <div class="layout__content__container__filter__filters__content">
                <div class="label">
                    TYPE
                </div>
                <MultiSelect v-model="type" :options="typeOptions" />
            </div>

            <div class="layout__content__container__filter__filters__content">
                <div class="label">
                    SPORT
                </div>
                <SportFilterSelect v-model="sports" :sports="sportOptions" />
            </div>

            <div class="layout__content__container__filter__filters__content">
                <div class="label">
                    BUY-IN
                </div>
                <MultiSelect v-model="buyIn" :options="buyInOptions" />
            </div>

            <div class="layout__content__container__filter__filters__content">
                <div class="label">
                    TIME FRAME
                </div>
                <MultiSelect v-model="timeFrame" :options="timeFrameOptions" />
            </div>

            <div class="layout__content__container__filter__filters__content">
                <div class="label">
                    PLAYERS
                </div>
                <MultiSelect v-model="playersLimit" :options="playersLimitOptions" />
            </div>

            <div class="layout__content__container__filter__filters__content">
                <div class="label">
                    UPCOMING ONLY
                </div>
                <input id="upcoming" type="checkbox" v-model="upcoming" class="checkbox" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import MultiSelect from "../../components/MultiSelect.vue";
import SportFilterSelect from "../../../general/components/SportFilterSelect.vue";
import { BuyInType, PlayersLimitType, TimeFrame, TournamentType } from "../../types/tournament";
import { mapEnumToSelecOptions } from "../../../general/utils/utils";
import { mapFields } from "../../store/utils";
import { Sport } from "../../../general/types/sport";

export default Vue.extend({
    name: "FilterContainer",
    components: { MultiSelect, SportFilterSelect },

    computed: {
        typeOptions(): any {
            return [{ id: null, name: "All" }, ...mapEnumToSelecOptions(TournamentType)];
        },

        buyInOptions(): any {
            return [{ id: null, name: "All" }, ...mapEnumToSelecOptions(BuyInType)];
        },

        timeFrameOptions(): any {
            return [{ id: null, name: "All" }, ...mapEnumToSelecOptions(TimeFrame)];
        },

        playersLimitOptions(): any {
            return [{ id: null, name: "All" }, ...mapEnumToSelecOptions(PlayersLimitType)];
        },

        sportOptions(): Sport[] {
            return this.$stock.state.sport.sports;
        },

        ...mapFields("tournamentList", [
            "buyIn",
            "playersLimit",
            "timeFrame",
            "type",
            "search",
            "sports",
            "upcoming",
        ]),
    },
});
</script>
