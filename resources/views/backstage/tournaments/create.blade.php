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
    @section('title', 'Create tournament')

{{-- FORM --}}
    @section('form_method', 'POST')
    @section('form_action', route('tournaments.store'))
    @section('form_laravelCsrf', csrf_field())
    @section('form_laravelMethod', method_field('POST'))
    @section('form_disabled', '')
        @section('avatar_value', '')
        @section('avatar_class_error', error('avatar', $errors))

        @section('name_value', '')
        @section('name_class_error', error('name', $errors))

        @section('type_value', '')
        @section('type_class_error', error('type', $errors))

        @section('prize_pool_value', '')
        @section('prize_pool_class_error', error('prize_pool', $errors))

        @section('players_limit_value', '')
        @section('players_limit_class_error', error('players_limit', $errors))

        @section('buy_in_value', '')
        @section('buy_in_class_error', error('buy_in', $errors))
  
        @section('commission_value', $config->commission)
        @section('commission_class_error', error('commission', $errors))
        
        @section('chips_value', $config->chips)
        @section('chips_class_error', error('chips', $errors))

        @section('late_register_value', '')
        @section('late_register_class_error', error('late_register', $errors))

        @section('state_value', '')
        @section('state_class_error', error('state', $errors))

        @section('form-loader_caption', 'CREATING...')

{{-- BUTTONS --}}
    @section('buttonSave_formId', 'form')
    @section('buttonSave_formType', 'submit')

    @section('HTML-formDelete')
        {{--    --}}
    @endsection
