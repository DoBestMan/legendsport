@extends('backstage.layout')

@section('HTML-js')
    <script type="text/javascript" src="{{ mix('/backstage/js/config.js') }}"></script>
@endsection

@section('HTML-main')
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="ui-title">@yield('title')</h1>
            </div>
        </div>

        <hr>

        <form @submit.prevent="updateConfig">
            <div inside="keep_completed" class="form-row form-group">
                <div class="col-2 text-right">
                    <label for="keep_completed" class="">Keep completed tournaments</label>
                </div>

                <div class="col-1">
                    <input
                        type="number"
                        name="config[keep_completed]"
                        id="keep_completed"
                        class="form-control text-right"
                        v-model="keepCompleted"
                        min="1"
                        required
                    >

                    @error('keep_completed')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <label class="col-auto col-form-label text-muted">
                    Days that a completed tournament still appears on the tournament list
                </label>
            </div>

            <h2>Default values</h2>

            <hr>

            <div inside="commission" class="form-row form-group">
                <div class="col-2 text-right">
                    <label for="commission" class="col-form-label">Commission</label>
                </div>

                <div class="col-1">
                    <money
                        id="commission"
                        class="form-control text-right"
                        placeholder=""
                        v-model="commission"
                        v-bind="money"
                    ></money>

                    <input
                        type="hidden"
                        name="config[commission]"
                        v-model="commission"
                        required
                    />

                    @error('chips')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <label class="col-auto col-form-label text-muted">
                    Commission by default when creating a tournament.
                </label>
            </div>

            <div inside="chips" class="form-row form-group">
                <div class="col-2 text-right">
                    <label for="chips" class="col-form-label">Chips</label>
                </div>

                <div class="col-1">
                    <money
                        id="chips"
                        class="form-control text-right"
                        placeholder=""
                        v-model="chips"
                        v-bind="formatNumber"
                        max=90
                    ></money>

                    <input
                        type="hidden"
                        name="config[chips]"
                        v-model="chips"
                        required
                    />

                    @error('chips')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <label class="col-auto col-form-label text-muted">
                    Chips by default when creating a tournament.
                </label>
            </div>

            <h2>API providers</h2>

            <hr>

            <div inside="providers" class="form-row form-group">
                <div class="col-2 text-right">
                    <label for="providers" class="col-form-label">Bet 365</label>
                </div>

                <div class="col-1">
                    <input v-model="providers" class="form-control text-right" type="checkbox" value="bet365" />

                    @error('providers')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <label class="col-auto col-form-label text-muted">
                    Enable/Disable provider
                </label>
            </div>
            <div inside="providers" class="form-row form-group">
                <div class="col-2 text-right">
                    <label for="providers" class="col-form-label">Test data</label>
                </div>

                <div class="col-1">
                    <input v-model="providers" class="form-control text-right" type="checkbox" value="testdata" />

                    @error('providers')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <label class="col-auto col-form-label text-muted">
                    Enable/Disable provider
                </label>
            </div>
            <div inside="providers" class="form-row form-group">
                <div class="col-2 text-right">
                    <label for="providers" class="col-form-label">SportsData.io NBA</label>
                </div>

                <div class="col-1">
                    <input v-model="providers" class="form-control text-right" type="checkbox" value="sportsdata.io/nba" />

                    @error('providers')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <label class="col-auto col-form-label text-muted">
                    Enable/Disable provider
                </label>
            </div>

            @yield('HTML-btnAction')
        </form>
    </div>
@endsection
