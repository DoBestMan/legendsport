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
    <div class="container">
        <div name="titleFrm" class="row">
            <div class="col">
                <h1 class="ui-title">@yield('title')</h1>
            </div>
        </div>

        <hr>

            <div id="formsFrm">
                <form id="form"
                    method="@yield("form_method")"
                    action="@yield("form_action")"
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
                                        v-bind:class="[errors['name']?'form-control is-invalid':'form-control']"
                                        @yield('form_disabled')
                                        v-model="name"
                                        required
                                    >

                                    {{-- <small class="form-text text-muted">description</small> --}}

                                    <div v-if="errors['name']" class="invalid-feedback">The name field is required.</div>
                                </div>
                            </div>

                            <div id="players_limitFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="players_limit" class="col-form-label">Players limit</label>
                                </div>

                                <div class="col-12 col-lg-4">
                                    <select name="players_limit"
                                        id="players_limit"
                                        v-bind:class="[errors['players_limit']?'form-control is-invalid':'form-control']"
                                        v-model="playersLimit"
                                        @yield('form_disabled')
                                        required
                                        >
                                        <option value='Heads-Up' @yield('players_limit_selected_Heads_Up')>Heads-Up</option>
                                        <option value='Full' @yield('players_limit_selected_Full')>Full</option>
                                        <option value='Unlimited' @yield('players_limit_selected_false')>Unlimited</option>
                                    </select>

                                    {{-- <small class="form-text text-muted">description</small> --}}

                                    <div v-if="errors['players_limit']" class="invalid-feedback">Players limit field is required</div>
                                </div>
                            </div>

                            <div id="buy_inFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="buy_in" class="col-form-label">Buy-in</label>
                                </div>

                                <div class="col-12 col-lg-2">
                                    <money
                                        id="buy_in"
                                        v-bind:class="[errors['buy_in']?'form-control text-right is-invalid':'form-control  text-right']"
                                        value="@yield('buy_in_value')"
                                        placeholder=""
                                        @yield('form_disabled')
                                        v-model="buyIn"
                                        v-bind="money"
                                    ></money>

                                    {{-- <small class="form-text text-muted">description</small> --}}


                                    <input type="hidden"
                                        name="buy_in"
                                        v-model="buyIn"
                                        required
                                    >
                                    <div v-if="errors['buy_in']" class="invalid-feedback">The buy in field is required.</div>
                                </div>
                            </div>

                            <div id="commissionFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="commission" class="col-form-label">Commission</label>
                                </div>

                                <div class="col-12 col-lg-2">
                                    <money
                                        id="commission"
                                        v-bind:class="[errors['commission']?'form-control text-right is-invalid':'form-control text-right']"
                                        class="form-control text-right @yield('commision')"
                                        value="@yield('commission_value')"
                                        placeholder=""
                                        @yield('form_disabled')
                                        v-model="commission"
                                        v-bind="money"
                                    ></money>

                                    {{-- <small class="form-text text-muted">description</small> --}}

                                    <input type="hidden"
                                        name="commission"
                                        v-model="commission"
                                        required
                                    >

                                    <div v-if="errors['commission']" class="invalid-feedback">@{{ errors['commission'] }}</div>
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
                                        class="form-control text-right @yield('chips_class_error')"
                                        value="@yield('chips_value')"
                                        v-bind:class="[errors['chips']?'form-control text-right is-invalid':'form-control text-right']"
                                        placeholder=""
                                        @yield('form_disabled')
                                        v-model="chips"
                                        v-bind="formatNumber"
                                    ></money>

                                    {{-- <small class="form-text text-muted">description</small> --}}

                                    <input type="hidden"
                                        name="chips"
                                        v-model="chips"
                                        required
                                    >
                                    <div v-if="errors['chips']" class="invalid-feedback">@{{ errors['chips'] }}</div>
                                </div>
                            </div>

                            <div id="late_registerFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="late_register" class="col-form-label">Late register</label>
                                </div>

                                <div class="col-12 col-lg-4">
                                    <select name="late_register"
                                        id="late_register"
                                        v-bind:class="[errors['late_register']?'form-control is-invalid':'form-control']"
                                        v-model="lateRegister"
                                        @yield('form_disabled')
                                        required
                                        >
                                        <option></option>
                                        <option value=1 @yield('late_register_selected_true')>Yes</option>
                                        <option value=0 @yield('late_register_selected_false')>No</option>
                                    </select>

                                    {{-- <small class="form-text text-muted">description</small> --}}
                                    <div v-if="errors['late_register']" class="invalid-feedback">This field is required.</div>
                                </div>

                                <template v-if="lateRegister == true">
                                    <div class="col-12 col-lg-2">
                                        <input type="text"
                                            name="late_register_rule['interval']"
                                            id="interval"
                                            class="form-control @yield('interval_class_error')"
                                            value="@yield('interval_value')"
                                            placeholder="Interval"
                                            @yield('form_disabled')
                                            v-model="interval"
                                            required
                                        >

                                        {{-- <small class="form-text text-muted">description</small> --}}

                                        @error('interval')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-lg-2">
                                        <input type="text"
                                            name="late_register_rule['value']"
                                            id="value"
                                            class="form-control @yield('value_class_error')"
                                            value="@yield('late_register_rule_value_value')"
                                            placeholder="Value"
                                            @yield('form_disabled')
                                            v-model="lateRegisterValue"
                                            required
                                        >

                                        {{-- <small class="form-text text-muted">description</small> --}}

                                        @error('value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </template>
                            </div>

                            <div id="prize_poolFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="prize_pool" class="col-form-label">Prize pool</label>
                                </div>

                                <div class="col-12 col-lg-3">
                                    <select id="prize_pool"
                                        name="prize_pool[type]"
                                        class="form-control @yield('prize_pool_class_error')"
                                        v-model="prizePool"
                                        @yield('form_disabled')
                                        required
                                        >
                                        <option></option>
                                        <option value='Auto'>Auto</option>
                                        <option value='Fixed'>Fixed</option>
                                    </select>

                                    {{-- <small class="form-text text-muted">description</small> --}}

                                    @error('prize_pool')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <template v-if="prizePool == 'Fixed'">
                                    <div class="col-12 col-lg-2 text-right">
                                        <label for="fixed_value" class="col-form-label">Value</label>
                                    </div>

                                    <div class="col-12 col-lg-2">
                                        <input type="text"
                                            name="prize_pool[fixed_value]"
                                            id="fixed_value"
                                            class="form-control @yield('fixed_value_class_error')"
                                            value="@yield('fixed_value')"
                                            placeholder=""
                                            @yield('form_disabled')
                                            v-model="prizePoolValue"
                                            required
                                        >

                                        {{-- <small class="form-text text-muted">description</small> --}}

                                        @error('fixed_value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </template>

                                <template>
                                    <div class="col-12 col-lg-2">
                                        <input type="hidden"
                                            name="prize_pool[fixed_value]"
                                            id="fixed_value"
                                            class="form-control @yield('fixed_value_class_error')"
                                            value=""
                                            placeholder=""
                                            @yield('form_disabled')
                                            required
                                        >

                                        @error('fixed_value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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
                                        v-bind:class="[errors['prizes']?'form-control is-invalid':'form-control']"
                                        v-model="prizes"
                                        @yield('form_disabled')
                                        required
                                        >
                                        <option></option>
                                        <option value='Auto'>Auto</option>
                                        <option value='Fixed'>Fixed</option>
                                    </select>
                                    {{-- <small class="form-text text-muted">description</small> --}}
                                    <div v-if="errors['prizes']" class="invalid-feedback">@{{ errors['prizes'] }}</div>
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
                                        @yield('form_disabled')
                                        v-model="state"
                                        required
                                        >
                                        <option></option>
                                        <option value="Announced" @yield('state_selected_announced')>Announced</option>
                                        <option value="Registering" @yield('state_selected_registering')>Registering</option>
                                        <option value="Late registering" @yield('state_selected_late_registering')>Late registering</option>
                                        <option value="Running" @yield('state_selected_running')>Running</option>
                                        <option value="Complete" @yield('state_selected_complete')>Completed</option>
                                        <option value="Cancel" @yield('state_selected_cancel')>Cancel</option>
                                    </select>

                                    {{-- <small class="form-text text-muted">description</small> --}}
                                    <div v-if="errors['state']" class="invalid-feedback">The state field is required.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                </form>
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

            <div id="buttonsFrm" class="form-row form-group">
                <div class="col-1">
                    <button id="buttonBack" class="btn btn-light btn-link"
                        onclick="window.location='{{ route('tournaments.index') }}';"
                    >Return</button>
                </div>

                <div class="offset-2 offset-lg-1 col-1">
                    <button class="btn btn-dark"
                        v-on:click="saveEvents()"
                    >Save</button>
                </div>
            </div>
    </div>
@endsection