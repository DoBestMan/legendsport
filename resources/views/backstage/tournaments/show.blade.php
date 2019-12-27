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
    @section('title', 'Update tournament')

{{-- FORM --}}
    @section('form_method', 'POST')
    @section('form_action', route('tournaments.update', $tournament))
    @section('form_laravelCsrf', csrf_field())
    @section('form_laravelMethod', method_field('PUT'))
    @section('form_disabled', 'disabled')

        @if ($tournament->avatar)
            @section('avatar_selected_true', 'selected')
        @else
            @section('avatar_selected_false', 'selected')
        @endif

        @section('name_value', $tournament->name)

        @if ($tournament->type)
            @section('type_selected_single', 'selected')
        @else
            @section('type_selected_false', 'selected')
        @endif

        @section('prize_pool_value', $tournament->prize_pool)

        @section('players_limit_value', $tournament->players_limit)

        @section('buy_in_value', $tournament->buy_in)

        @section('chips_value', $tournament->chips)

        @section('commission_value', $tournament->commission)

        @if ($tournament->late_register)
            @section('late_register_selected_true', 'selected')
        @else
            @section('late_register_selected_false', 'selected')
        @endif

            <!-- mejorar -->
            @if ( $tournament->state == 'Announced')
            @section('state_selected_announced', 'selected')
            @endif
            @if ( $tournament->state == 'Late registering')
                @section('state_selected_late_registering', 'selected')
            @endif
            @if ( $tournament->state == 'Running')
                @section('state_selected_running', 'selected')
            @endif
            @if ( $tournament->state == 'Complete')
                @section('state_selected_complete', 'selected')
            @endif
            @if ( $tournament->state == 'Cancel')
                @section('state_selected_cancel', 'selected')
            @endif

        @section('form-loader_caption', 'ACTUALIZANDO...')

{{-- BUTTONS --}}
    @section('buttonSave_formType', '')
    @section('buttonSave_formId', '')
