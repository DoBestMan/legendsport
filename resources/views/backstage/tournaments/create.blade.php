{{-- EXTEND --}}
    @extends('backstage.tournaments.layout')

{{-- VARS --}}
    @section('title', 'Create tournament')

{{-- HTML --}}
@section('HTML-main')
    @parent

    <div class="container">
        <div name="titleFrm" class="row">
            <div class="col">
                <h1 class="ui-title">@yield('title')</h1>
            </div>
        </div>

        <hr/>

        <form @submit.prevent="createTournament">
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
                :time-frame.sync="timeFrame"
                :min-bots.sync="minBots"
                :max-bots.sync="maxBots"
                :add-bots.sync="addBots"
                :player-bots.sync="playerBots"
                :auto-end.sync="autoEnd"
            ></tournament-form>

            <hr/>

            <div>
                <selected-event-list :events="selectedEvents" @@remove="removeEvent"></selected-event-list>

                <button
                    type="button"
                    class="btn btn-dark my-3"
                    @@click="showModalAvailableEventList"
                >
                    Show available events
                </button>

                <modal-available-event-list
                    v-model="isModalAvailableEventListVisible"
                    @@select="includeEvent"
                ></modal-available-event-list>
            </div>

            <hr/>

            <div id="buttonsFrm" class="form-row form-group">
                <div class="col-2">
                    <a class="btn btn-light btn-link" href="{{ route('tournaments.index') }}">
                        Return
                    </a>
                </div>

                <div class="offset-2 offset-lg-1 col-2">
                    <action-button class="btn-dark">
                        Save
                    </action-button>
                </div>
            </div>
        </form>
    </div>
@endsection
