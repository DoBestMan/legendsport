{{-- EXTEND --}}
    @extends('backstage.layout')

{{-- VARS --}}

{{-- CSS --}}
    @section('HTML-cssVendors')

    @endsection

    @section('HTML-css')
    <link rel="stylesheet" href="{{ mix('/backstage/css/backstage.css') }}">
    @endsection

{{-- JS --}}
    @section('HTML-js')
        <script type="text/javascript" src="{{ mix('/backstage/js/tournaments.js') }}"></script>
    @endsection
