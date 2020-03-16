<template>
    <div class="form-row">
        <div class="col-4">
            <div id="nameFrm" class="form-row form-group">
                <div class="col-4 text-right">
                    <label for="name" class="col-form-label">Name</label>
                </div>

                <div class="col-8">
                    <input
                        type="text"
                        id="name"
                        name="name"
                        :class="[errors['name'] ? 'form-control is-invalid' : 'form-control']"
                        :value="name"
                        @input="$emit('update:name', $event.target.value)"
                        required
                    />

                    <div v-if="errors['name']" class="invalid-feedback">
                        {{ errors["name"]["0"] }}
                    </div>
                </div>
            </div>

            <div id="players_limitFrm" class="form-row form-group">
                <div class="col-4 text-right">
                    <label for="players_limit" class="col-form-label">Players limit</label>
                </div>

                <div class="col-8">
                    <select
                        name="players_limit"
                        id="players_limit"
                        :class="[
                            errors['players_limit'] ? 'form-control is-invalid' : 'form-control',
                        ]"
                        :value="playersLimit"
                        @change="$emit('update:playersLimit', $event.target.value)"
                        required
                    >
                        <option></option>
                        <option :value="PlayersLimitType.HeadsUp">Heads-Up</option>
                        <option :value="PlayersLimitType.SingleTable">Single table</option>
                        <option :value="PlayersLimitType.Unlimited">Unlimited</option>
                    </select>

                    <div v-if="errors['players_limit']" class="invalid-feedback">
                        {{ errors["players_limit"]["0"] }}
                    </div>
                </div>
            </div>

            <div id="time_frameFrm" class="form-row form-group">
                <div class="col-4 text-right">
                    <label for="time_frame" class="col-form-label">Time frame</label>
                </div>

                <div class="col-8">
                    <select
                        name="time_frame"
                        id="time_frame"
                        :class="[errors['time_frame'] ? 'form-control is-invalid' : 'form-control']"
                        :value="timeFrame"
                        @change="$emit('update:timeFrame', $event.target.value)"
                        required
                    >
                        <option></option>
                        <option :value="TimeFrame.Daily">Daily</option>
                        <option :value="TimeFrame.Weekly">Weekly</option>
                        <option :value="TimeFrame.Monthly">Monthly</option>
                        <option :value="TimeFrame.SeasonLong">Season long</option>
                    </select>

                    <div v-if="errors['time_frame']" class="invalid-feedback">
                        {{ errors["time_frame"]["0"] }}
                    </div>
                </div>
            </div>

            <div id="buy_inFrm" class="form-row form-group">
                <div class="col-4 text-right">
                    <label for="buy_in" class="col-form-label">Buy-in</label>
                </div>

                <div class="col-8">
                    <money
                        id="buy_in"
                        :class="[
                            errors['buy_in']
                                ? 'form-control text-right is-invalid'
                                : 'form-control  text-right',
                        ]"
                        placeholder=""
                        :value="buyIn"
                        @input="$emit('update:buyIn', $event)"
                        v-bind="money"
                    ></money>

                    <input
                        type="hidden"
                        name="buy_in"
                        :value="buyIn"
                        @input="$emit('update:buyIn', $event.target.value)"
                        required
                    />
                    <div v-if="errors['buy_in']" class="invalid-feedback">
                        {{ errors["buy_in"]["0"] }}
                    </div>
                </div>
            </div>

            <div id="commissionFrm" class="form-row form-group">
                <div class="col-4 text-right">
                    <label for="commission" class="col-form-label">Commission</label>
                </div>

                <div class="col-8">
                    <money
                        id="commission"
                        :class="[
                            errors['commission']
                                ? 'form-control text-right is-invalid'
                                : 'form-control text-right',
                        ]"
                        :value="commission"
                        @input="$emit('update:commission', $event)"
                        v-bind="money"
                    ></money>

                    <input
                        type="hidden"
                        name="commission"
                        :value="commission"
                        @input="$emit('update:commission', $event.target.value)"
                        required
                    />

                    <div v-if="errors['commission']" class="invalid-feedback">
                        {{ errors["commission"]["0"] }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-8">
            <div id="chipsFrm" class="form-row form-group">
                <div class="col-3 text-right">
                    <label for="chips" class="col-form-label">Chips</label>
                </div>

                <div class="col-4">
                    <money
                        id="chips"
                        :class="[
                            errors['chips']
                                ? 'form-control text-right is-invalid'
                                : 'form-control text-right',
                        ]"
                        :value="chips"
                        @input="$emit('update:chips', $event)"
                        v-bind="formatNumber"
                    ></money>

                    <input
                        type="hidden"
                        name="chips"
                        :value="chips"
                        @input="$emit('update:chips', $event.target.value)"
                        required
                    />
                    <div v-if="errors['chips']" class="invalid-feedback">
                        {{ errors["chips"]["0"] }}
                    </div>
                </div>
            </div>

            <div
                v-if="playersLimit == 'Unlimited'"
                id="late_registerFrm"
                class="form-row form-group"
            >
                <div class="col-3 text-right">
                    <label for="late_register" class="col-form-label">Late register</label>
                </div>

                <div class="col-2">
                    <select
                        name="late_register"
                        id="late_register"
                        :class="[
                            errors['late_register'] ? 'form-control is-invalid' : 'form-control',
                        ]"
                        :value="lateRegister"
                        @change="$emit('update:lateRegister', $event.target.value)"
                        required
                    >
                        <option></option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>

                    <div v-if="errors['late_register']" class="invalid-feedback">
                        {{ errors["late_register"]["0"] }}
                    </div>
                </div>

                <template v-if="lateRegister">
                    <div class="col-12 col-lg-1 text-right">
                        <label for="late_register_rule" class="col-form-label">Interval</label>
                    </div>
                    <div class="col-12 col-lg-2">
                        <select
                            name="late_register_rule['interval']"
                            :class="[
                                errors['late_register_rule.interval']
                                    ? 'form-control is-invalid'
                                    : 'form-control',
                            ]"
                            :value="interval"
                            @change="$emit('update:interval', $event.target.value)"
                            required
                        >
                            <option></option>
                            <option value="seconds">Seconds</option>
                            <option value="minutes">Minutes</option>
                            <option value="hours">Hours</option>
                            <option value="days">Days</option>
                        </select>

                        <div v-if="errors['late_register_rule.interval']" class="invalid-feedback">
                            {{ errors["late_register_rule.interval"]["0"] }}
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <input
                            type="number"
                            :class="[
                                errors['late_register_rule.value']
                                    ? 'form-control is-invalid'
                                    : 'form-control',
                            ]"
                            placeholder="Value"
                            :value="lateRegisterValue"
                            @input="$emit('update:lateRegisterValue', $event.target.value)"
                            min="1"
                            :max="interval == 'seconds' || interval == 'minutes' ? '60' : '100'"
                            required
                        />

                        <div v-if="errors['late_register_rule.value']" class="invalid-feedback">
                            {{ errors["late_register_rule.value"]["0"] }}
                        </div>
                    </div>
                </template>
            </div>

            <div id="prize_poolFrm" class="form-row form-group">
                <div class="col-12 col-lg-3 text-right">
                    <label for="prize_pool" class="col-form-label">Prize pool</label>
                </div>

                <div class="col-12 col-lg-2">
                    <select
                        id="prize_pool"
                        name="prize_pool[type]"
                        :class="[
                            errors['prize_pool.type'] ? 'form-control is-invalid' : 'form-control',
                        ]"
                        class="form-control"
                        :value="prizePool"
                        @change="$emit('update:prizePool', $event.target.value)"
                        required
                    >
                        <option></option>
                        <option value="Auto">Auto</option>
                        <option value="Fixed">Fixed</option>
                    </select>

                    <div v-if="errors['prize_pool.type']" class="invalid-feedback">
                        {{ errors["prize_pool.type"]["0"] }}
                    </div>
                </div>

                <template v-if="prizePool == 'Fixed'">
                    <div class="col-12 col-lg-1 text-right">
                        <label for="fixed_value" class="col-form-label">Value</label>
                    </div>
                    <div class="col-12 col-lg-2">
                        <money
                            id="fixed_value"
                            :class="[
                                errors['prize_pool.fixed_value']
                                    ? 'form-control text-right is-invalid'
                                    : 'form-control text-right',
                            ]"
                            :value="prizePoolValue"
                            @input="$emit('update:prizePoolValue', $event)"
                            v-bind="money"
                        ></money>
                        <input type="hidden" class="form-control" placeholder="" required />
                        <div v-if="errors['prize_pool.fixed_value']" class="invalid-feedback">
                            {{ errors["prize_pool.fixed_value"]["0"] }}
                        </div>
                    </div>
                </template>
            </div>

            <div id="prizesFrm" class="form-row form-group">
                <div class="col-3 text-right">
                    <label for="prizes" class="col-form-label">Prizes</label>
                </div>

                <div class="col-4">
                    <select
                        id="prizes"
                        name="prizes[type]"
                        :class="[
                            errors['prizes.type'] ? 'form-control is-invalid' : 'form-control',
                        ]"
                        :value="prizes"
                        @change="$emit('update:prizes', $event.target.value)"
                        required
                    >
                        <option></option>
                        <option value="Auto">Auto</option>
                        <option value="Fixed">Fixed</option>
                    </select>
                    <div v-if="errors['prizes.type']" class="invalid-feedback">
                        {{ errors["prizes.type"]["0"] }}
                    </div>
                </div>
            </div>

            <div id="stateFrm" class="form-row form-group">
                <div class="col-12 col-lg-3 text-right">
                    <label for="state" class="col-form-label">State</label>
                </div>

                <div class="col-12 col-lg-4">
                    <select
                        name="state"
                        id="state"
                        :class="[errors['state'] ? 'form-control is-invalid' : 'form-control']"
                        :value="state"
                        @change="$emit('update:state', $event.target.value)"
                        required
                    >
                        <option></option>
                        <option :value="TournamentState.Announced">Announced</option>
                        <option :value="TournamentState.Registering">Registering</option>
                        <option :value="TournamentState.LateRegistering">Late registering</option>
                        <option :value="TournamentState.Running">Running</option>
                        <option :value="TournamentState.Completed">Completed</option>
                        <option :value="TournamentState.Cancel">Cancel</option>
                    </select>

                    <div v-if="errors['state']" class="invalid-feedback">
                        {{ errors["state"]["0"] }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { TournamentState } from "../../../general/types/tournament";
import { PlayersLimitType, TimeFrame } from "../../../app/types/tournament";

export default {
    name: "TournamentForm",

    props: [
        "buyIn",
        "chips",
        "commission",
        "interval",
        "lateRegister",
        "lateRegisterValue",
        "name",
        "playersLimit",
        "prizePool",
        "prizePoolValue",
        "prizes",
        "state",
        "timeFrame",
        "errors",
    ],

    data() {
        return {
            money: {
                decimal: ",",
                thousands: ",",
                prefix: "$ ",
                suffix: "",
                precision: 0,
            },

            formatNumber: {
                decimal: ",",
                thousands: ",",
                precision: 0,
            },
        };
    },

    computed: {
        TournamentState() {
            return TournamentState;
        },

        PlayersLimitType() {
            return PlayersLimitType;
        },

        TimeFrame() {
            return TimeFrame;
        },
    },
};
</script>
