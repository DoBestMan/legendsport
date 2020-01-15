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
                <h1>@yield('title')</h1>
            </div>
        </div>

        <form id="form"
            method="@yield("form_method")"
            action="@yield("form_action")"
            >
            @yield("form_laravelCsrf")
            @yield("form_laravelMethod")

            <div id="commissionFrm" class="form-row form-group">
                <div class="col-lg-2 text-right">
                    <label for="commission" class="col-form-label">Commission</label>
                </div>

                <div class="col-12 col-lg-1">
                    <money
                        id="commission"
                        class="form-control text-right @yield('commission_class_error')"
                        value="@yield('commission_value')"
                        placeholder=""
                        @yield('form_disabled')
                        v-model="commission"
                        v-bind="money"
                    ></money>

                    <small class="form-text text-muted">description</small>

                    <input type="hidden"
                        name="config[commission]"
                        v-model="commission"
                    >

                    @error('chips')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div id="chipsFrm" class="form-row form-group">
                <div class="col-12 col-lg-2 text-right">
                    <label for="chips" class="col-form-label">Chips</label>
                </div>

                <div class="col-12 col-lg-1">
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

                    <small class="form-text text-muted">description</small>

                    <input type="hidden"
                        name="config[chips]"
                        v-model="chips"
                    >

                    @error('chips')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div id="keep_completedFrm" class="form-row form-group">
                <div class="col-12 col-lg-2 text-right">
                    <label for="keep_completed" class="col-form-label">Keep completed</label>
                </div>

                <div class="col-12 col-lg-1">
                    <input type="number"
                        name="config[keep_completed]"
                        id="keep_completed"
                        class="form-control text-right @yield('keep_completed_class_error')"
                        value="@yield('keep_completed_value')"
                        placeholder=""
                        @yield('form_disabled')
                    >

                    <small class="form-text text-muted">description</small>

                    @error('keep_completed')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <span class="col-form-label">days</span>
            </div>
            
        </form>

        @yield('HTML-btnAction')
    </div>
@endsection
