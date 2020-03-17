<template>
    <div class="row">
        <div class="col-1">
            <label for="type" class="control-title">Type</label>
            <MultiSelect id="type" :options="typeOptions" v-model="tournamentListStore.type" />
        </div>

        <div class="col-3">
            <label for="sports" class="control-title">Sport</label>
            <SportSelect id="sports" v-model="tournamentListStore.sports"></SportSelect>
        </div>

        <div class="col-1">
            <label for="buy-in" class="control-title">Buy-In</label>
            <MultiSelect id="buy-in" :options="buyInOptions" v-model="tournamentListStore.buyIn" />
        </div>

        <div class="col-1">
            <label for="time-frame" class="control-title">Time Frame</label>
            <MultiSelect
                id="time-frame"
                :options="timeFrameOptions"
                v-model="tournamentListStore.timeFrame"
            />
        </div>

        <div class="col-1">
            <label for="players-limit" class="control-title">Players</label>
            <MultiSelect
                id="players-limit"
                :options="playersLimitOptions"
                v-model="tournamentListStore.playersLimit"
            />
        </div>

        <div class="col-2">
            <label for="upcoming" class="control-title">Show upcoming only</label>
            <input
                type="checkbox"
                v-model="tournamentListStore.upcoming"
                id="upcoming"
                class="form-control control-input"
            />
        </div>

        <div class="col-3">
            <label for="search" class="control-title">Search</label>

            <input
                id="search"
                type="text"
                v-model="tournamentListStore.search"
                class="form-control control-input"
            />
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import tournamentListStore from "../../stores/tournamentListStore";
import MultiSelect from "../../components/MultiSelect";
import SportSelect from "../../../general/components/SportSelect.vue";
import { BuyInType, PlayersLimitType, TimeFrame, TournamentType } from "../../types/tournament";
import { mapEnumToSelecOptions } from "../../../general/utils/utils";

export default Vue.extend({
    name: "FilterContainer",
    components: { MultiSelect, SportSelect },

    computed: {
        typeOptions() {
            return [{ id: null, name: "All" }, ...mapEnumToSelecOptions(TournamentType)];
        },
        buyInOptions() {
            return [{ id: null, name: "All" }, ...mapEnumToSelecOptions(BuyInType)];
        },

        timeFrameOptions() {
            return [{ id: null, name: "All" }, ...mapEnumToSelecOptions(TimeFrame)];
        },

        playersLimitOptions() {
            return [{ id: null, name: "All" }, ...mapEnumToSelecOptions(PlayersLimitType)];
        },

        tournamentListStore() {
            return tournamentListStore;
        },
    },
});
</script>
