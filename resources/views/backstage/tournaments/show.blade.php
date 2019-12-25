@php
    $isIndex = false;

    $hasButtonBack = true;
    $hasButtonAdd = false;
    $hasButtonDel = true;
    $hasButtonSave = true;
@endphp

{{-- EXTEND --}}
    @extends('backstage.tournaments.layout')

{{-- VARS --}}
    @section('title', 'Update tournaments')

{{-- FORM --}}
    @section('form_method', 'POST')
    @section('form_action', route('tournaments.update', $tournament))
    @section('form_laravelCsrf', csrf_field())
    @section('form_laravelMethod', method_field('PUT'))
    @section('form_disabled', 'disabled')

        @if ($tournament->avatar)
            @section('valid_selected_true', 'selected')
        @else
            @section('valid_selected_false', 'selected')
        @endif

        @section('name_value', $tournament->name)

        @if ($tournament->type)
            @section('valid_selected_true', 'selected')
        @else
            @section('valid_selected_false', 'selected')
        @endif

        @section('prize_pool_value', $tournament->prize_pool)

        @section('players_limit_value', $tournament->players_limit)

        @section('buy_in_value', $tournament->buy_in)

        @section('commission_value', $tournament->commission)

        @section('chips_value', $tournament->chips)

        @if ($tournament->late_register)
            @section('valid_selected_true', 'selected')
        @else
            @section('valid_selected_false', 'selected')
        @endif

        @section('state_value', $tournament->state)

        @section('form-loader_caption', 'ACTUALIZANDO...')

{{-- BUTTONS --}}
    @section('buttonSave_formType', '')
    @section('buttonSave_formId', '')
