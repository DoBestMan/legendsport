{{-- EXTEND --}}
    @extends('backstage.tournaments.layout')

{{-- VARS --}}
    @section('title', 'Update tournaments')

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

        <form @submit.prevent="updateTournament">
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

            <hr />

            <div id="buttonsFrm" class="form-row form-group">
                <div class="col-1">
                    <a class="btn btn-light btn-link" href="{{ route('tournaments.index') }}">
                        Return
                    </a>
                </div>

                <div class="offset-2 offset-lg-1 col-1">
                    <action-button class="btn-dark">
                        Update
                    </action-button>
                </div>

                <div class="offset-5 col-1 offset-lg-8 col-lg-1">
                    <button
                        type="button"
                        class="btn btn-danger"
                        title="Delete"
                        @click='openDeleteModal(@json($tournament->id), @json($tournament->name))'
                    >
                        Delete
                    </button>
                </div>
            </div>
        </form>
    </div>

    <modal-delete
        v-model="modalDeleteId"
        :text-description="modalDeleteDescription"
        @@destroy="deleteTournament(modalDeleteId)"
    ></modal-delete>
@endsection
