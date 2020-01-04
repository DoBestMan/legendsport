{{-- EXTEND --}}
    @extends('backstage.layout')

{{-- VARS --}}

{{-- CSS --}}
    @section('HTML-cssVendors')
        <script src="https://cdn.jsdelivr.net/npm/v-money@0.8.1/dist/v-money.min.js"></script>
    @endsection

    @section('HTML-css')
        <link rel="stylesheet" href="{{ asset('backstage/css/config.css') }}">
    @endsection

{{-- JS --}}
    @section('HTML-jsVendors')

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
                    <label for="commission" class="col-form-label">commission</label>
                </div>

                <div class="col-lg-1">
                    <input type="text"
                        name="config[commission]"
                        id="commission"
                        class="form-control @yield('commission_class_error')"
                        value="@yield('commission_value')"
                        placeholder=""
                        @yield('form_disabled')
                    >

                    @error('commission')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div id="chipsFrm" class="form-row form-group">
                <div class="col-12 col-lg-3 text-right">
                    <label for="chips" class="col-form-label">Chips</label>
                </div>

                <div class="col-12 col-lg-2">
                    <money
                        id="chips"
                        class="form-control text-right @yield('chips_class_error')"
                        value="@yield('chips_value')"
                        placeholder=""
                        @yield('form_disabled')
                        v-model="chips"
                        v-bind="formatNumber"
                    ></money>

                    <input type="hidden"
                        name="config[chips]"
                        v-model="chips"
                    >

                    @error('chips')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div id="keep_completeFrm" class="form-row form-group">
                <div class="col-12 col-lg-2 text-right">
                    <label for="keep_complete" class="col-form-label">Keep complete</label>
                </div>

                <div class="col-12 col-lg-1">
                    <input type="text"
                        name="config[keep_complete]"
                        id="keep_complete"
                        class="form-control @yield('keep_complete_class_error')"
                        value="@yield('keep_complete_value')"
                        placeholder=""
                        @yield('form_disabled')
                    >

                    @error('keep_complete')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
        </form>

        @yield('HTML-btnAction')
    </div>
@endsection
