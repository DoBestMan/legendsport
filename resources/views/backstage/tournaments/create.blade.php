@php
    $isIndex = false;
    $isShow = false;

    $hasButtonBack = true;
    $hasButtonAdd = false;
    $hasButtonDel = false;
    $hasButtonSave = true;
@endphp

{{-- EXTEND --}}
    @extends('backstage.tournaments.layout')

{{-- VARS --}}
    @section('title', 'Create tournaments')

{{-- FORM --}}
    @section('form_method', 'POST')
    @section('form_action', route('tournaments.store'))
    @section('form_laravelCsrf', csrf_field())
    @section('form_laravelMethod', method_field('POST'))
    @section('form_disabled', '')

        @section('avatar_value', '')

        @section('name_value', '')

        @section('type_value', '')

        @section('prize_pool_value', '')

        @section('players_limit_value', '')

        @section('buy_in_value', '')
  
        @section('commission_value', $config->commission)
        
        @section('chips_value', $config->chips)

        @section('late_register_value', '')

        @section('state_value', '')

        @section('form-loader_caption', 'CREANDO...')

{{-- BUTTONS --}}
    @section('buttonSave_formId', 'form')
    @section('buttonSave_formType', 'submit')

    @section('HTML-formDelete')
        {{--    --}}
    @endsection
