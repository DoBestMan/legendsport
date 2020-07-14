@extends('backstage.tournaments.layout')

@section('HTML-main')
    @parent

    <div class="container">
        <div name="titleFrm" class="row">
            <div class="col">
                <h1 class="ui-title">Show tournament</h1>
            </div>
        </div>

        <hr>

        <fieldset disabled>
            <tournament-form
                :errors="errors"
                :name.sync="name"
                :players-limit.sync="playersLimit"
                :buy-in.sync="buyIn"
                :chips.sync="chips"
                :commission.sync="commission"
                :interval.sync="interval"
                :late-register.sync="lateRegister"
                :late-register-value.sync="lateRegisterValue"
                :prize-pool.sync="prizePool"
                :prize-pool-value.sync="prizePoolValue"
                :state.sync="state"
                :min-bots="minBots"
                :max-bots="maxBots"
                :add-bots="addBots"
                :player-bots="playerBots"
            ></tournament-form>
        </fieldset>

        <hr>

        <selected-event-list :events="selectedEvents"></selected-event-list>

        <hr/>

        <div id="buttonsFrm" class="form-row form-group">
            <div class="col-1">
                <a class="btn btn-light btn-link" href="{{ route('tournaments.index') }}">
                    Return
                </a>
            </div>
        </div>
    </div>
@endsection
