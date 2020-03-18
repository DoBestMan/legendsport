@extends('app.layout')

@section('homeActive','active')

@section('HTML-css')
<link rel="stylesheet" href="{{ mix('/app/css/home.css') }}">
@endsection

@section('HTML-js')
<script type="text/javascript" src="{{ mix('/app/js/home.js') }}"></script>
@endsection

@section('HTML-main')
<tournament-container></tournament-container>
@endsection
