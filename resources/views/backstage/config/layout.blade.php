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

            <div id="chipsFrm" class="form-row form-group">
                <div class="col-12 col-lg-2 text-right">
                    <label for="chips" class="col-form-label">chips</label>
                </div>

                <div class="col-12 col-lg-3">
                    <input type="text"
                        name="chips"
                        id="chips"
                        class="form-control @yield('chips_class_error')"
                        value="@yield('chips_value')"
                        placeholder=""
                        @yield('form_disabled')
                    >

                    @error('chips')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div id="commissionFrm" class="form-row form-group">
                <div class="col-lg-2 text-right">
                    <label for="commission" class="col-form-label">commission</label>
                </div>

                <div class="col-lg-3">
                    <input type="text"
                        name="commission"
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

            <form-loader :sending="formIsSending" caption="ACTUALIZANDO..."></form-loader>
        </form>

        @yield('HTML-btnAction')
    </div>
@endsection
