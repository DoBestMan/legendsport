{{-- EXTEND --}}
    @extends('backstage.layout')

{{-- VARS --}}

{{-- CSS --}}
    @section('HTML-cssVendors')

    @endsection

    @section('HTML-css')
        <link rel="stylesheet" href="{{ asset('backstage/css/config.css') }}">
    @endsection

{{-- JS --}}
    @section('HTML-jsVendors')
        <script src="https://cdn.jsdelivr.net/npm/v-money@0.8.1/dist/v-money.min.js"></script>
    @endsection

    @section('HTML-js')
        <script type="text/javascript" language="javascript" src="{{ asset('backstage/js/config.js') }}"></script>
    @endsection

{{-- HTML --}}
@section('HTML-main')
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="ui-title">@yield('title')</h1>
            </div>
        </div>

        <hr>

        <form id="form"
            method="@yield("form_method")"
            action="@yield("form_action")"
            >
            @yield("form_laravelCsrf")
            @yield("form_laravelMethod")

            <div inside="keep_completed" class="form-row form-group">
                <div class="col-2 text-right">
                    <label for="keep_completed" class="">Keep completed tournaments</label>
                </div>

                <div class="col-1">
                    <input type="number"
                        name="config[keep_completed]"
                        id="keep_completed"
                        class="form-control text-right @yield('keep_completed_class_error')"
                        value="@yield('keep_completed_value')"
                        placeholder=""
                        @yield('form_disabled')
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
                        class="form-control text-right @yield('commission_class_error')"
                        value="@yield('commission_value')"
                        placeholder=""
                        @yield('form_disabled')
                        v-model="commission"
                        v-bind="money"
                    ></money>

                    <input type="hidden"
                        name="config[commission]"
                        v-model="commission"
                    >

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
                        class="form-control text-right @yield('chips_class_error')"
                        value="@yield('chips_value')"
                        placeholder=""
                        @yield('form_disabled')
                        v-model="chips"
                        v-bind="formatNumber"
                        max=90
                    ></money>

                    <input type="hidden"
                        name="config[chips]"
                        v-model="chips"
                    >

                    @error('chips')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <label class="col-auto col-form-label text-muted">
                    Chips by default when creating a tournament.
                </label>
            </div>
        </form>

        @yield('HTML-btnAction')
    </div>
@endsection
