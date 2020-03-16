
{{-- EXTEND --}}
    @extends('backstage.tournaments.layout')

{{-- VARS --}}
    @section('title', 'Show tournament')

@section('HTML-main')
    @parent

    <div class="container">
        <div name="titleFrm" class="row">
            <div class="col">
                <h1 class="ui-title">@yield('title')</h1>
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
                :prizes.sync="prizes"
                :state.sync="state"
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
