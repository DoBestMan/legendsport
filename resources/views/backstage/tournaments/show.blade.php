@php
    $isIndex = false;
    $isCreate = false;
    $isShow = false;

    $hasButtonBack = true;
    $hasButtonAdd = false;
    $hasButtonDel = true;
    $hasButtonSave = true;
@endphp

{{-- EXTEND --}}
    @extends('backstage.tournaments.layout')

{{-- VARS --}}
    @section('title', 'Show tournament')

{{-- FORM --}}
    @section('form_method', 'POST')
    @section('form_action', route('tournaments.update', $tournament))
    @section('form_laravelCsrf', csrf_field())
    @section('form_laravelMethod', method_field('PUT'))
    @section('form_disabled', 'disabled')

    @if ($tournament->avatar)
            @section('avatar_selected_true', 'selected')
        @else
            @section('avatar_selected_false', 'selected')
        @endif
        @section('avatar_class_error', error('avatar', $errors))

        @section('name_value', $tournament->name)
        @section('name_class_error', error('name', $errors))

        @if ($tournament->type == 'Single')
            @section('type_selected_single', 'selected')
        @else
            @section('type_selected_multiple', 'selected')
        @endif
        @section('type_class_error', error('type', $errors))

        @if ($tournament->prize_pool['type'] == 'Auto')
            @section('prize_pool_selected_auto', 'selected')
        @else
            @section('prize_pool_selected_fixed', 'selected')
        @endif
        @section('fixed_value', $tournament->prize_pool['fixed_value'])

        @section('players_limit_value', $tournament->players_limit)
        @section('players_limit_class_error', error('players_limit', $errors))

        @section('buy_in_value', $tournament->buy_in)
        @section('buy_in_class_error', error('buy_in', $errors))

        @section('commission_value', $tournament->commission)
        @section('commission_class_error', error('commission', $errors))

        @section('chips_value', $tournament->chips)
        @section('chips_class_error', error('chips', $errors))

        @if ($tournament->late_register)
            @section('late_register_selected_true', 'selected')
        @else
            @section('late_register_selected_false', 'selected')
        @endif
        @section('late_register_class_error', error('late_register', $errors))

        <!-- mejorar -->
            @if ( $tournament->state == 'Announced')
                @section('state_selected_announced', 'selected')
            @endif
            @if ( $tournament->state == 'Late registering')
                @section('state_selected_late_registering', 'selected')
            @endif
            @if ( $tournament->state == 'Running')
                @section('state_selected_running', 'selected')
            @endif
            @if ( $tournament->state == 'Complete')
                @section('state_selected_complete', 'selected')
            @endif
            @if ( $tournament->state == 'Cancel')
                @section('state_selected_cancel', 'selected')
            @endif
            @section('state_class_error', error('state', $errors))
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
                                        >
                                        <option value='Heads-Up'>Heads-Up</option>
                                        <option value='Full'>Full</option>
                                        <option value='Unlimited'>Unlimited</option>
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
                                        v-model="buyIn"
                                        v-bind="money"
                                        @yield('form_disabled')
                                    ></money>

                                    {{-- <small class="form-text text-muted">description</small> --}}


                                    <input type="hidden"
                                        name="buy_in"
                                        v-model="buyIn"
                                        @yield('form_disabled')
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
                                        v-model="commission"
                                        v-bind="money"
                                        @yield('form_disabled')
                                    ></money>


                                    {{-- <small class="form-text text-muted">description</small> --}}
                                    <input type="hidden"
                                        name="commission"
                                        v-model="commission"
                                        @yield('form_disabled')
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
                                        class="form-control text-right"
                                        value="@yield('chips_value')"
                                        v-bind:class="[errors['chips']?'form-control text-right is-invalid':'form-control text-right']"
                                        placeholder=""
                                        v-model="chips"
                                        v-bind="formatNumber"
                                        @yield('form_disabled')
                                    ></money>

                                    {{-- <small class="form-text text-muted">description</small> --}}

                                    <input type="hidden"
                                        name="chips"
                                        v-model="chips"
                                        @yield('form_disabled')
                                    >
                                    <div v-if="errors['chips']" class="invalid-feedback">@{{ errors['chips'] }}</div>
                                </div>
                            </div>

                            <div id="late_registerFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="late_register" class="col-form-label">Late register</label>
                                </div>

                                <div class="col-12 col-lg-2">
                                    <select name="late_register"
                                        id="late_register"
                                        v-bind:class="[errors['late_register']?'form-control is-invalid':'form-control']"
                                        v-model="lateRegister"
                                        required
                                        @yield('form_disabled')
                                        >
                                        <option value=1>Yes</option>
                                        <option value=0>No</option>
                                    </select>

                                    {{-- <small class="form-text text-muted">description</small> --}}
                                    <div v-if="errors['late_register']" class="invalid-feedback">This field is required.</div>
                                </div>

                                <template v-if="lateRegister == true">
                                    <div class="col-12 col-lg-2 text-right">
                                        <label for="late_register_rule" class="col-form-label">Interval</label>
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        <select name="late_register"
                                            name="late_register_rule['interval']"
                                            class="form-control"
                                            v-model="interval"
                                            placeholder="Select Interval"
                                            required
                                            @yield('form_disabled')
                                            >seconds, minutes, hours, days
                                            <option value="seconds">Seconds</option>
                                            <option value="minutes">Minutes</option>
                                            <option value="hours">Hours</option>
                                            <option value="days">Days</option>
                                        </select>

                                        {{-- <small class="form-text text-muted">description</small> --}}

                                        @error('interval')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-lg-2">
                                        <input type="number"
                                            name="late_register_rule['value']"
                                            id="value"
                                            class="form-control"
                                            value="@yield('late_register_rule_value_value')"
                                            placeholder="Value"
                                            v-model="lateRegisterValue"
                                            min="1"
                                            :max="(interval == 'seconds' || interval == 'minutes')?'60':'100'"
                                            required
                                            @yield('form_disabled')
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
                                        <input type="number"
                                            name="prize_pool[fixed_value]"
                                            id="fixed_value"
                                            class="form-control @yield('fixed_value_class_error')"
                                            value="@yield('fixed_value')"
                                            placeholder=""
                                            @yield('form_disabled')
                                            min="0"
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
            </div>
    </div>
@endsection
{{-- BUTTONS --}}
    @section('buttonSave_formType', '')
    @section('buttonSave_formId', '')
