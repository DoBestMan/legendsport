{{-- EXTEND --}}
    @extends('backstage.tournaments.layout')

{{-- VARS --}}
    @section('title', 'Create tournament')

{{-- FORM --}}
    @section('form_method', 'POST')
    @section('form_action', route('tournaments.store'))
    @section('form_laravelCsrf', csrf_field())
    @section('form_laravelMethod', method_field('POST'))
    @section('form_disabled', '')

{{-- BUTTONS --}}
    @section('buttonSave_formId', 'form')
    @section('buttonSave_formType', 'submit')

    @section('HTML-formDelete')
{{--    --}}
    @endsection
{{-- HTML --}}
@section('HTML-main')
<Toasts></Toasts>
    <div>
        <div name="titleFrm" class="row">
            <div class="col">
                <h1 class="ui-title">@yield('title')</h1>
            </div>
        </div>
        <hr>

            <div id="formsFrm">
                <form id="form"
                    method="@yield('form_method')"
                    action="@yield('form_action')"
                    >
                    @yield("form_laravelCsrf")
                    @yield("form_laravelMethod")

                    <div class="form-row">
                        <div class="col-6">
                            <div id="nameFrm" class="form-row form-group">
                                <div class="col-3 text-right">
                                    <label for="name" class="col-form-label">Name</label>
                                </div>

                                <div class="col-7">
                                    <input type="text"
                                        id="name"
                                        name="name"
                                        :class="[errors['name']?'form-control is-invalid':'form-control']"
                                        v-model="name"
                                        required
                                    >

                                    {{-- <small class="form-text text-muted">description</small> --}}

                                    <div v-if="errors['name']" class="invalid-feedback">@{{ errors['name']['0'] }}
                                    </div>
                                </div>
                            </div>

                            <div id="players_limitFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="players_limit" class="col-form-label">Players limit</label>
                                </div>

                                <div class="col-12 col-lg-4">
                                    <select
                                        name="players_limit"
                                        id="players_limit"
                                        :class="[errors['players_limit']?'form-control is-invalid':'form-control']"
                                        v-model="playersLimit"
                                        required
                                        >
                                        <option></option>
                                        <option value='Heads-Up'>Heads-Up</option>
                                        <option value='Full'>Full</option>
                                        <option selected value='Unlimited'>Unlimited</option>
                                    </select>

                                    {{-- <small class="form-text text-muted">description</small> --}}

                                    <div v-if="errors['players_limit']" class="invalid-feedback">@{{errors['players_limit']['0'] }}</div>
                                </div>
                            </div>

                            <div id="buy_inFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="buy_in" class="col-form-label">Buy-in</label>
                                </div>

                                <div class="col-12 col-lg-2">
                                    <money
                                        id="buy_in"
                                        :class="[errors['buy_in']?'form-control text-right is-invalid':'form-control  text-right']"
                                        v-model="buyIn"
                                        v-bind="money"
                                    ></money>

                                    {{-- <small class="form-text text-muted">description</small> --}}


                                    <input type="hidden"
                                        name="buy_in"
                                        v-model="buyIn"
                                        required
                                    >
                                    <div v-if="errors['buy_in']" class="invalid-feedback">@{{ errors['buy_in']['0'] }}</div>
                                </div>
                            </div>

                            <div id="commissionFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="commission" class="col-form-label">Commission</label>
                                </div>

                                <div class="col-12 col-lg-2">
                                    <money
                                        id="commission"
                                        :class="[errors['commission']?'form-control text-right is-invalid':'form-control text-right']"
                                        v-model="commission"
                                        v-bind="money"
                                    ></money>

                                    {{-- <small class="form-text text-muted">description</small> --}}

                                    <input type="hidden"
                                        name="commission"
                                        v-model="commission"
                                        required
                                    >

                                    <div v-if="errors['commission']" class="invalid-feedback">
                                    @{{ errors['commission']['0'] }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-xl-6">
                            <div id="chipsFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="chips" class="col-form-label">Chips</label>
                                </div>

                                <div class="col-12 col-lg-2">
                                    <money
                                        id="chips"
                                        :class="[errors['chips']?'form-control text-right is-invalid':'form-control text-right']"
                                        v-model="chips"
                                        v-bind="formatNumber"
                                    ></money>

                                    {{-- <small class="form-text text-muted">description</small> --}}

                                    <input type="hidden"
                                        name="chips"
                                        v-model="chips"
                                        required
                                    >
                                    <div v-if="errors['chips']" class="invalid-feedback">@{{ errors['chips']['0'] }}</div>
                                </div>
                            </div>
                            <div v-if="playersLimit == 'Unlimited'" id="late_registerFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="late_register" class="col-form-label">Late register</label>
                                </div>

                                <div class="col-12 col-lg-2">
                                    <select name="late_register"
                                        id="late_register"
                                        :class="[errors['late_register']?'form-control is-invalid':'form-control']"
                                        v-model="lateRegister"
                                        required
                                        >
                                        <option></option>
                                        <option value=1>Yes</option>
                                        <option value=0>No</option>
                                    </select>

                                    {{-- <small class="form-text text-muted">description</small> --}}
                                    <div v-if="errors['late_register']" class="invalid-feedback">@{{ errors['late_register']['0'] }}</div>
                                </div>

                                <template v-if="lateRegister == true">
                                    <div class="col-12 col-lg-1 text-right">
                                        <label for="late_register_rule" class="col-form-label">Interval</label>
                                    </div>
                                    <div class="col-12 col-lg-2">
                                        <select name="late_register"
                                            name="late_register_rule['interval']"
                                            :class="[errors['late_register_rule.interval'] ? 'form-control is-invalid':'form-control']"
                                            v-model="interval"
                                            required
                                            >
                                            <option></option>
                                            <option value="seconds">Seconds</option>
                                            <option value="minutes">Minutes</option>
                                            <option value="hours">Hours</option>
                                            <option value="days">Days</option>
                                        </select>

                                        {{-- <small class="form-text text-muted">description</small> --}}
                                        <div v-if="errors['late_register_rule.interval']" class="invalid-feedback">@{{ errors['late_register_rule.interval']['0'] }}</div>
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        <input type="number"
                                            :class="[errors['late_register_rule.value']?'form-control is-invalid':'form-control']"
                                            placeholder="Value"
                                            v-model="lateRegisterValue"
                                            min="1"
                                            :max="(interval == 'seconds' || interval == 'minutes')?'60':'100'"
                                            required
                                        >

                                        {{-- <small class="form-text text-muted">description</small> --}}
                                        <div v-if="errors['late_register_rule.value']" class="invalid-feedback">@{{ errors['late_register_rule.value']['0'] }}</div>
                                    </div>
                                </template>
                            </div>

                            <div id="prize_poolFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="prize_pool" class="col-form-label">Prize pool</label>
                                </div>

                                <div class="col-12 col-lg-2">
                                    <select id="prize_pool"
                                        name="prize_pool[type]"
                                        :class="[errors['prize_pool.type']?'form-control is-invalid':'form-control']"
                                        class="form-control"
                                        v-model="prizePool"
                                        required
                                        >
                                        <option></option>
                                        <option value='Auto'>Auto</option>
                                        <option value='Fixed'>Fixed</option>
                                    </select>

                                    {{-- <small class="form-text text-muted">description</small> --}}
                                    <div v-if="errors['prize_pool.type']"class="invalid-feedback">
                                        @{{errors['prize_pool.type']['0']}}
                                    </div>
                                </div>

                                <template v-if="prizePool == 'Fixed'">
                                    <div class="col-12 col-lg-1 text-right">
                                        <label for="fixed_value" class="col-form-label">Value</label>
                                    </div>
                                    <div class="col-12 col-lg-2">
                                        <money
                                            id="fixed_value"
                                            :class="[errors['prize_pool.fixed_value']?'form-control text-right is-invalid':'form-control text-right']"
                                            v-model="prizePoolValue"
                                            v-bind="money"
                                        ></money>
                                        <input type="hidden"
                                            id="fixed_value"
                                            class="form-control"
                                            placeholder=""
                                            required
                                        >
                                    {{-- <small class="form-text text-muted">description</small> --}}
                                        <div v-if="errors['prize_pool.fixed_value']" class="invalid-feedback">@{{ errors['prize_pool.fixed_value']['0'] }}</div>
                                    </div>
                                </template>
                            </div>

                            <div id="prizesFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="prizes" class="col-form-label">Prizes</label>
                                </div>

                                <div class="col-12 col-lg-3">
                                    <select id="prizes"
                                        name="prizes[type]"
                                        v-bind:class="[errors['prizes.type']?'form-control is-invalid':'form-control']"
                                        v-model="prizes"
                                        required
                                        >
                                        <option></option>
                                        <option value='Auto'>Auto</option>
                                        <option value='Fixed'>Fixed</option>
                                    </select>
                                    {{-- <small class="form-text text-muted">description</small> --}}
                                    <div v-if="errors['prizes.type']" class="invalid-feedback">@{{ errors['prizes.type']['0'] }}</div>
                                </div>
                            </div>

                            <div id="stateFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="state" class="col-form-label">State</label>
                                </div>

                                <div class="col-12 col-lg-4">
                                    <select name="state"
                                        id="state"
                                        v-bind:class="[errors['state']?'form-control is-invalid':'form-control']"
                                        v-model="state"
                                        required
                                        >
                                        <option></option>
                                        <option value="Announced">Announced</option>
                                        <option value="Registering">Registering</option>
                                        <option value="Late registering">Late registering</option>
                                        <option value="Running">Running</option>
                                        <option value="Complete">Completed</option>
                                        <option value="Cancel">Cancel</option>
                                    </select>

                                    {{-- <small class="form-text text-muted">description</small> --}}
                                    <div v-if="errors['state']" class="invalid-feedback">@{{errors['state']['0']}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                </form>
                <div class="container">
                <form id="FormSaveUser">
                    @yield("form_laravelCsrf")
                    @yield("form_laravelMethod")

                    <div class="row form-group">
                        <div class="col-5">
                            <label for="SelectSport">Sport</label>

                            <select
                                id="SelectSport"
                                name="SelectSport"
                                class="form-control form-control-sm"
                                v-model="selected"
                                >
                                <option value="">All</option>
                                <option value="NBA">NBA</option>
                                <option value="NCAAB">NCAAB</option>
                                <option value="NCAAF">NCAAF</option>
                                <option value="NFL">NFL</option>
                                <option value="NHL">NHL</option>
                                <option value="SOCCER">SOCCER</option>
                                <option value="MMA">MMA (UFC)</option>
                                <option value="KHL">KHL</option>
                                <option value="AHL">AHL</option>
                                <option value="SHL">SHL</option>
                            </select>
                        </div>

                        <div class="col-4">
                            <label for="">Date game</label>

                            <input type="date"
                                name="sportDate"
                                class="form-control form-control-sm">
                        </div>

                        <div class="offset-2 offset-lg-1 col-1">
                            <label for=""></label>
                            <button class="btn btn-dark"
                                type="button"
                                v-on:click="getEvents(selected)"
                            >Filter</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="headerFixed table table-sm table-light table-striped table-borderless table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" width="280px">Date</th>
                                <th scope="col" width="220px">Home team</th>
                                <th scope="col" width="230px">Away Team</th>
                                <th scope="col" width="200px">Sport</th>
                                <th scope="col" width="200px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="event in events">
                                <tr>
                                    <td class="text-truncate" width="300px">@{{ event.MatchTime }}</td>
                                    <td class="text-truncate" width="210px">@{{ event.HomeTeam }}</td>
                                    <td class="text-truncate" width="230px">@{{ event.AwayTeam }}</td>
                                    <td class="text-truncate" width="200px" v-html="switchNameSport(event.Sport)">@{{ event.Sport }}</td>
                                    <td class="text-truncate" width="200px">
                                        <button
                                            id="enlaceajax"
                                            type="button"
                                            class="btn btn-dark"
                                            v-on:click="includeEvent(event)"
                                        >include</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    <hr>
                </div>
                <div class="table-responsive">
                    <h5>Included Events</h5>
                    <table class="headerFixed table table-sm table-light table-striped table-borderless table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" width="280px">Date</th>
                                <th scope="col" width="220px">Home team</th>
                                <th scope="col" width="230px">Away Team</th>
                                <th scope="col" width="200px">Sport</th>
                                <th scope="col" width="200px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="selected in selectedEvents">
                                <tr>
                                    <td class="text-truncate" width="300px">@{{ selected.MatchTime }}</td>
                                    <td class="text-truncate" width="210px">@{{ selected.HomeTeam }}</td>
                                    <td class="text-truncate" width="230px">@{{ selected.AwayTeam }}</td>
                                    <td class="text-truncate" width="200px"v-html="switchNameSport(selected.Sport)">@{{ selected.Sport }}</td>
                                    <td class="text-truncate" width="200px">
                                        <button
                                            id="enlaceajax"
                                            type="button"
                                            class="btn btn-dark"
                                            v-on:click="removeEvent(selected)"
                                        >remove</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    <hr>
                </div>
                @yield('HTML-formDelete')
            </div>
            </div>
            <div class="container">
                <div id="buttonsFrm" class="form-row form-group">
                    <div class="col-2">
                        <button id="buttonBack" class="btn btn-light btn-link"
                            onclick="window.location='{{ route('tournaments.index') }}';"
                        >Return</button>
                    </div>

                    <div class="offset-2 offset-lg-1 col-2">
                        <button class="btn btn-dark"
                            v-on:click="saveEvents()"
                        >Save</button>
                    </div>
                </div>
            </div>
    </div>
@endsection