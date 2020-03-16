<template>
    <div class="row">
        <div class="col-1">
            <label for="type" class="control-title">Type</label>

            <select id="type" v-model="tournamentListStore.type" class="form-control control-input">
                <option :value="null" class="form-control control-input">All</option>
                <option :value="TournamentType.Single" class="form-control control-input">
                    Single
                </option>
                <option :value="TournamentType.Multiple" class="form-control control-input">
                    Multiple
                </option>
            </select>
        </div>
        <div class="col-3">
            <label for="sports" class="control-title">Sport</label>
            <SportSelect id="sports" v-model="tournamentListStore.sports"></SportSelect>
        </div>
        <div class="col-1">
            <label for="buyin" class="control-title">Buy-In</label>

            <select
                id="buyin"
                v-model="tournamentListStore.buyIn"
                class="form-control control-input"
            >
                <option :value="null" class="form-control control-input">All</option>
                <option :value="BuyInType.Freeroll" class="form-control control-input">
                    Freeroll
                </option>
                <option :value="BuyInType.Low" class="form-control control-input">Low</option>
                <option :value="BuyInType.Medium" class="form-control control-input">Medium</option>
                <option :value="BuyInType.High" class="form-control control-input">High</option>
            </select>
        </div>

        <div class="col-1">
            <label for="time-frame" class="control-title">Time Frame</label>

            <select id="time-frame" v-model="timeFrame" class="form-control control-input">
                <option value="" class="form-control control-input">All</option>
            </select>
        </div>

        <div class="col-1">
            <label for="players-limit" class="control-title">Players</label>

            <select
                id="players-limit"
                v-model="tournamentListStore.playersLimit"
                class="form-control control-input"
            >
                <option :value="null" class="form-control control-input">All</option>
                <option :value="PlayersLimitType.HeadsUp" class="form-control control-input">
                    Heads-Up
                </option>
                <option :value="PlayersLimitType.SingleTable" class="form-control control-input">
                    Single table
                </option>
                <option :value="PlayersLimitType.Unlimited" class="form-control control-input">
                    Unlimited
                </option>
            </select>
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
import SportSelect from "../../../general/components/SportSelect.vue";
import { BuyInType, PlayersLimitType, TournamentType } from "../../types/tournament";

export default Vue.extend({
    name: "FilterContainer",
    components: { SportSelect },

    data() {
        return {
            timeFrame: "",
        };
    },

    computed: {
        tournamentListStore() {
            return tournamentListStore;
        },

        BuyInType() {
            return BuyInType;
        },

        TournamentType() {
            return TournamentType;
        },

        PlayersLimitType() {
            return PlayersLimitType;
        },
    },
});
</script>
