<template>
    <div class="modal modal--active" style="top: 283px;">
        <div class="modal__component">
            <div class="label label--large">
                TYPE
            </div>
            <MultiSelect v-model="type" :options="typeOptions" />
        </div>

        <div class="modal__component">
            <div class="label label--large">
                SPORT
            </div>
            <SportFilterSelect v-model="sports" :sports="sportOptions" />
        </div>

        <div class="modal__component">
            <div class="label label--large">
                BUY-IN
            </div>
            <MultiSelect v-model="buyIn" :options="buyInOptions" />
        </div>

        <div class="modal__component">
            <div class="label label--large">
                TIME FRAME
            </div>
            <MultiSelect v-model="timeFrame" :options="timeFrameOptions" />
        </div>

        <div class="modal__component">
            <div class="label label--large">
                PLAYERS
            </div>
            <MultiSelect v-model="playersLimit" :options="playersLimitOptions" />
        </div>

        <div class="modal__component">
            <div class="label label--large">
                UPCOMING ONLY
            </div>
            <input id="upcoming" class="checkbox" type="checkbox" v-model="upcoming" />
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import MultiSelect from "../components/MultiSelect.vue";
import SportFilterSelect from "../../general/components/SportFilterSelect.vue";
import { BuyInType, PlayersLimitType, TimeFrame, TournamentType } from "../types/tournament";
import { mapEnumToSelecOptions } from "../../general/utils/utils";
import { mapFields } from "../store/utils";
import { Sport } from "../../general/types/sport";
export default Vue.extend({
    name: "MobileHomeFilter",

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
            "sports",
            "upcoming",
        ]),
    },
});
</script>
