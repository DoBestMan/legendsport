<template>
    <!-- <div class="row">
        <div class="col-1">
            <label for="type" class="control-title">Type</label>
            <MultiSelect id="type" :options="typeOptions" v-model="type" />
        </div>

        <div class="col-3">
            <label for="sports" class="control-title">Sport</label>
            <SportSelect id="sports" :sports="sportOptions" v-model="sports"></SportSelect>
        </div>

        <div class="col-1">
            <label for="buy-in" class="control-title">Buy-In</label>
            <MultiSelect id="buy-in" :options="buyInOptions" v-model="buyIn" />
        </div>

        <div class="col-1">
            <label for="time-frame" class="control-title">Time Frame</label>
            <MultiSelect id="time-frame" :options="timeFrameOptions" v-model="timeFrame" />
        </div>

        <div class="col-1">
            <label for="players-limit" class="control-title">Players</label>
            <MultiSelect id="players-limit" :options="playersLimitOptions" v-model="playersLimit" />
        </div>

        <div class="col-2" style="width: 200px">
            <div class="custom-control custom-checkbox multiline-checkbox">
                <input
                    id="upcoming"
                    type="checkbox"
                    v-model="upcoming"
                    class="form-control control-input custom-control-input my-error"
                    style="width: 40px"
                />
                <label for="upcoming" class="control-title custom-control-label checkbox-label"
                    >Show upcoming only</label
                ><br />
                <label
                    for="upcoming"
                    class="control-title custom-control-label checkbox-box"
                ></label>
            </div>
        </div>

        <div class="col-3">
            <label for="search" class="control-title">Search</label>

            <input id="search" type="text" v-model="search" class="form-control control-input" />
        </div>
    </div> -->

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
                <div class="dropdown">
                    <div class="form__control">
                        <input class="input" type="text" v-model="type" readonly="readonly" />
                        <div class="form__control__icon--right">
                            <i class="icon icon--micro icon--down"></i>
                        </div>
                    </div>
                    <div class="dropdown__content">
                        <div class="dropdown__content__item">
                            All
                            <i class="icon icon--smaller icon--check icon--color--yellow-1"></i>
                        </div>
                        <div class="dropdown__content__item">Free Roll</div>
                        <div class="dropdown__content__item">Low</div>
                        <div class="dropdown__content__item">Medium</div>
                        <div class="dropdown__content__item">High</div>
                    </div>
                </div>
            </div>
            <div class="layout__content__container__filter__filters__content">
                <div class="label">
                    SPORT
                </div>
                <div class="dropdown">
                    <div class="form__control">
                        <input class="input" type="text" v-model="sports" readonly="readonly" />
                        <div class="form__control__icon--right">
                            <i class="icon icon--micro icon--down"></i>
                        </div>
                    </div>
                    <div class="dropdown__content">
                        <div class="dropdown__content__item">
                            All
                            <i class="icon icon--smaller icon--check icon--color--yellow-1"></i>
                        </div>
                        <div class="dropdown__content__item">Free Roll</div>
                        <div class="dropdown__content__item">Low</div>
                        <div class="dropdown__content__item">Medium</div>
                        <div class="dropdown__content__item">High</div>
                    </div>
                </div>
            </div>
            <div class="layout__content__container__filter__filters__content">
                <div class="label">
                    BUY-IN
                </div>
                <div class="dropdown">
                    <div class="form__control">
                        <input class="input" type="text" v-model="buyIn" readonly="readonly" />
                        <div class="form__control__icon--right">
                            <i class="icon icon--micro icon--down"></i>
                        </div>
                    </div>
                    <div class="dropdown__content">
                        <div class="dropdown__content__item">
                            All
                            <i class="icon icon--smaller icon--check icon--color--yellow-1"></i>
                        </div>
                        <div class="dropdown__content__item">Free Roll</div>
                        <div class="dropdown__content__item">Low</div>
                        <div class="dropdown__content__item">Medium</div>
                        <div class="dropdown__content__item">High</div>
                    </div>
                </div>
            </div>
            <div class="layout__content__container__filter__filters__content">
                <div class="label">
                    TIME FRAME
                </div>
                <div class="dropdown">
                    <div class="form__control">
                        <input class="input" type="text" v-model="timeFrame" readonly="readonly" />
                        <div class="form__control__icon--right">
                            <i class="icon icon--micro icon--down"></i>
                        </div>
                    </div>
                    <div class="dropdown__content">
                        <div class="dropdown__content__item">
                            All
                            <i class="icon icon--smaller icon--check icon--color--yellow-1"></i>
                        </div>
                        <div class="dropdown__content__item">Free Roll</div>
                        <div class="dropdown__content__item">Low</div>
                        <div class="dropdown__content__item">Medium</div>
                        <div class="dropdown__content__item">High</div>
                    </div>
                </div>
            </div>
            <div class="layout__content__container__filter__filters__content">
                <div class="label">
                    PLAYERS
                </div>
                <div class="dropdown">
                    <div class="form__control">
                        <input
                            class="input"
                            type="text"
                            v-model="playersLimit"
                            readonly="readonly"
                        />
                        <div class="form__control__icon--right">
                            <i class="icon icon--micro icon--down"></i>
                        </div>
                    </div>
                    <div class="dropdown__content">
                        <div class="dropdown__content__item">
                            All
                            <i class="icon icon--smaller icon--check icon--color--yellow-1"></i>
                        </div>
                        <div class="dropdown__content__item">Free Roll</div>
                        <div class="dropdown__content__item">Low</div>
                        <div class="dropdown__content__item">Medium</div>
                        <div class="dropdown__content__item">High</div>
                    </div>
                </div>
            </div>
            <div class="layout__content__container__filter__filters__content">
                <div class="label">
                    UPCOMING ONLY
                </div>
                <input class="checkbox" type="checkbox" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import MultiSelect from "../../components/MultiSelect.vue";
import SportSelect from "../../../general/components/SportSelect.vue";
import { BuyInType, PlayersLimitType, TimeFrame, TournamentType } from "../../types/tournament";
import { mapEnumToSelecOptions } from "../../../general/utils/utils";
import { mapFields } from "../../store/utils";
import { Sport } from "../../../general/types/sport";

export default Vue.extend({
    name: "FilterContainer",
    components: { MultiSelect, SportSelect },

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
